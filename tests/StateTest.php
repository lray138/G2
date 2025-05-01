<?php

use lray138\G2\State;

describe('State Monad', function () {

    it('satisfies the Left Identity law', function () {
        $x = 10;
        $f = fn($n) => State::of($n * 2);

        $a = State::of($x)->bind($f);
        $b = $f($x);

        $init = 0;
        expect($a->run($init))->toBe($b->run($init));
    });

    it('satisfies the Right Identity law', function () {
        $m = State::of(5);
        $result = $m->bind(fn($x) => State::of($x));

        $init = 0;
        expect($result->run($init))->toBe($m->run($init));
    });

    it('satisfies the Associativity law', function () {
        $m = State::of(2);

        $f = fn($x) => State::of($x + 3);
        $g = fn($x) => State::of($x * 4);

        $a = $m->bind($f)->bind($g);
        $b = $m->bind(fn($x) => $f($x)->bind($g));

        $init = 0;
        expect($a->run($init))->toBe($b->run($init));
    });

    it('satisfies the Applicative Composition law', function () {
        $w = State::of(2);
        $u = State::of(fn($x) => $x + 3);
        $v = State::of(fn($x) => $x * 4);

        $left = $u->ap($v->ap($w));
        $leftVal = $left->run(0);

        $compose = fn($f) => fn($g) => fn($x) => $f($g($x));
        $right = State::of($compose)->ap($u)->ap($v)->ap($w);
        $rightVal = $right->run(0);

        expect($leftVal)->toBe($rightVal);
    });

    it('can read and update the state', function () {
        $increment = State::get()->bind(
            fn($s) => State::put($s + 1)->map(fn() => $s)
        );

        [$val, $state] = $increment->run(10);

        expect($val)->toBe(10);
        expect($state)->toBe(11);
    });

    it('composes state changes with bind', function () {
        $program = State::get()
            ->bind(fn($s) => State::put($s + 1))
            ->bind(fn() => State::get())
            ->bind(fn($s) => State::put($s * 2))
            ->bind(fn() => State::get());

        [$val, $finalState] = $program->run(3);

        expect($val)->toBe(8);        // (3 + 1) * 2
        expect($finalState)->toBe(8);
    });

});
