<?php

use lray138\G2\Maybe\Nothing;

it('throws an error if the constructor is accessed directly', function () {
    expect(function () {
        new Nothing();
    })->toThrow(Error::class);
});

describe('Nothing monad', function () {
    $unit = fn() => Nothing::unit(); // or `Nothing::instance()` if using singleton pattern

    it('ignores function in Left Identity', function () use ($unit) {
        $f = fn($x) => $x + 1;
        $result = $unit()->bind(fn($x) => $unit($f($x)));
        expect($result)->toBeInstanceOf(Nothing::class);
        expect($result->extract())->toBeNull(); // or whatever Nothing returns
    });

    it('satisfies the Right Identity law', function () use ($unit) {
        $m = $unit();
        $result = $m->bind($unit);
        expect($result)->toBeInstanceOf(Nothing::class);
        expect($result->extract())->toBeNull(); // or default Nothing value
    });

    it('satisfies the Associativity law', function () use ($unit) {
        $f = fn($x) => $unit($x + 1);
        $g = fn($x) => $unit($x * 2);

        $m = $unit();

        $left = $m->bind($f)->bind($g);
        $right = $m->bind(fn($x) => $f($x)->bind($g));

        expect($left)->toBeInstanceOf(Nothing::class);
        expect($right)->toBeInstanceOf(Nothing::class);
        expect($left->extract())->toBeNull();
        expect($right->extract())->toBeNull();
    });
    
});
