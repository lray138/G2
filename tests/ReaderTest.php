<?php 

use lray138\G2\Reader;

describe('Reader Monad', function () {

    it('satisfies the Left Identity law', function () {
        $x = 10;
        $f = fn($n) => Reader::of($n * 2);

        $a = Reader::of($x)->bind($f);
        $b = $f($x);

        $env = "ENV"; // any dummy env
        expect($a->run($env))->toBe($b->run($env));
    });

    it('satisfies the Right Identity law', function () {
        $m = Reader::of(5);
        $result = $m->bind(fn($x) => Reader::of($x));

        $env = "ENV";
        expect($result->run($env))->toBe($m->run($env));
    });

    it('satisfies the Associativity law', function () {
        $m = Reader::of(2);

        $f = fn($x) => Reader::of($x + 3);
        $g = fn($x) => Reader::of($x * 4);

        $a = $m->bind($f)->bind($g);
        $b = $m->bind(fn($x) => $f($x)->bind($g));

        $env = "ENV";
        expect($a->run($env))->toBe($b->run($env));
    });

    it('satisfies the Applicative Composition law', function () {
        $w = Reader::of(2);
    
        $u = Reader::of(fn($x) => $x + 3);
        $v = Reader::of(fn($x) => $x * 4);
    
        // Left side: u.ap(v.ap(w))
        $left = $u->ap($v->ap($w));
        $leftVal = $left->run([]);
    
        // Right side: of(compose)->ap(u)->ap(v)->ap(w)
        $compose = fn($f) => fn($g) => fn($x) => $f($g($x));
        $right = Reader::of($compose)->ap($u)->ap($v)->ap($w);
        $rightVal = $right->run([]);
    
        expect($leftVal)->toBe($rightVal);
    });

    it('can read from the environment', function () {
        $reader = Reader::ask()->map(fn($env) => $env['config']);

        $env = ['config' => 'value'];
        expect($reader->run($env))->toBe('value');
    });

    it('composes functions using bind', function () {
        $a = Reader::ask()
            ->bind(fn($env) => Reader::of($env['base'] + 1))
            ->bind(fn($x) => Reader::of($x * 2));

        $env = ['base' => 3];
        expect($a->run($env))->toBe(8); // (3 + 1) * 2
    });

});

