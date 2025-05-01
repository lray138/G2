<?php 

use lray138\G2\Writer;

describe('Writer Monad', function () {

    it('satisfies the Left Identity law', function () {
        $x = 10;
        $f = fn($n) => Writer::of($n * 2);

        $a = Writer::of($x)->bind($f);
        $b = $f($x);

        expect($a->run())->toBe($b->run());
    });

    it('satisfies the Right Identity law', function () {
        $m = Writer::of(5);
        $result = $m->bind(fn($x) => Writer::of($x));

        expect($result->run())->toBe($m->run());
    });

    it('satisfies the Associativity law', function () {
        $m = Writer::of(2);

        $f = fn($x) => Writer::of($x + 3);
        $g = fn($x) => Writer::of($x * 4);

        $a = $m->bind($f)->bind($g);
        $b = $m->bind(fn($x) => $f($x)->bind($g));

        expect($a->run())->toBe($b->run());
    });

    it('accumulates logs through bind', function () {
        $of = fn($x, $log) => Writer::of(fn () => [$x, $log]);
        $of = fn($x, $log) => Writer::of($x, $log);

        $a = $of(3, "Start")
            ->bind(fn($x) => $of($x + 2, "Add 2"))
            ->bind(fn($x) => $of($x * 2, "Multiply by 2"));

        [$value, $log] = $a->run();

        expect($value)->toBe(10);
        expect($log)->toBe("StartAdd 2Multiply by 2");
    });

    // it('short-circuits when null is encountered', function () {
    //     $of = fn($x, $log) => new Writer(fn () => [$x, ArrType::of([$log])]);

    //     $a = $of(5, "Init")
    //         ->bind(fn($x) => $of(null, "Kill switch"))
    //         ->bind(fn($x) => $of($x + 1, "This should not run"));

    //     [$value, $log] = $a->run();

    //     expect($value)->toBeNull();
    //     expect($log->unwrap())->toContain("Computation ended.");
    // });

});
