<?php 

use lray138\G2\{
    Str, 
    Writer
};

describe('Writer Monad', function () {

    it('satisfies the Left Identity law', function () {
        $x = 10;
        $f = fn($n) => Writer::of($n * 2, Str::mempty());

        $a = Writer::of($x)->bind($f);
        $b = $f($x);

        list($a_val) = $a->run();
        list($b_val) = $b->run();

        expect($a_val)->toBe($b_val);
    });

    it('satisfies the Right Identity law', function () {
        $m = Writer::of(5);
        $result = $m->bind(fn($x) => Writer::of($x));

        list($a_val, $a_log) = $result->run();
        list($b_val, $b_log) = $m->run();

        expect($a_val)->toBe($b_val);
    });

    it('satisfies the Associativity law', function () {
        $m = Writer::of(2);

        $f = fn($x) => Writer::of($x + 3);
        $g = fn($x) => Writer::of($x * 4);

        $a = $m->bind($f)->bind($g);
        $b = $m->bind(fn($x) => $f($x)->bind($g));

        list($a_val) = $a->run();
        list($b_val) = $b->run();

        expect($a_val)->toBe($b_val);
    });

    it('satisfies the Applicative Composition law', function () {
        $w = Writer::of(2);
    
        $u = Writer::of(fn($x) => $x + 3);
        $v = Writer::of(fn($x) => $x * 4);
    
        // Left side: u.ap(v.ap(w))
        $left = $u->ap($v->ap($w));
        [$leftVal] = $left->run();
    
        // Right side: of(compose)->ap(u)->ap(v)->ap(w)
        $compose = fn($f) => fn($g) => fn($x) => $f($g($x));
        $right = Writer::of($compose)->ap($u)->ap($v)->ap($w);
        [$rightVal] = $right->run();
    
        expect($leftVal)->toBe($rightVal);
    });

    it('short-circuits when null is encountered', function () {
        $of = fn($x, $log) => Writer::of($x, $log);

        $a = $of(5, Str::of("Init"))
            ->bind(fn($x) => $of(null, Str::of("Kill switch")))
            ->bind(fn($x) => $of($x + 1, Str::of("This should not run")));

        [$value, $log] = $a->run();

        expect($value)->toBeNull();
        expect($log->extract())->toContain("Kill switch");
    });

});

it('accumulates logs through bind', function () {
    //$of = fn($x, $log) => Writer::of(fn () => [$x, $log]);
    $of = fn($x, $log) => Writer::of($x, $log);

    $a = $of(3, Str::of("1"))
        ->bind(fn($x) => $of($x + 2, Str::of("2")))
        ->bind(fn($x) => $of($x * 2, Str::of("3")));

    [$value, $log] = $a->run();

    expect($value)->toBe(10);
    expect($log->extract())->toBe("123");
});