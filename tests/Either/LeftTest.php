<?php

use lray138\G2\Either\Left;

it('throws an error if the constructor is accessed directly', function () {
    expect(function () {
        new Left('error');
    })->toThrow(Error::class);
});

describe('Left monad', function () {
    $unit = fn() => Left::of('error'); // or static instance if needed

    it('ignores function in Left Identity', function () use ($unit) {
        $f = fn($x) => $x + 1;
        $result = $unit()->bind(fn($x) => $unit($f($x)));

        expect($result)->toBeInstanceOf(Left::class);
        expect($result->extract())->toBe('error');
    });

    it('satisfies the Right Identity law', function () use ($unit) {
        $m = $unit();
        $result = $m->bind($unit);

        expect($result)->toBeInstanceOf(Left::class);
        expect($result->extract())->toBe('error');
    });

    it('satisfies the Associativity law', function () use ($unit) {
        $f = fn($x) => $unit($x + 1);
        $g = fn($x) => $unit($x * 2);

        $m = $unit();

        $left = $m->bind($f)->bind($g);
        $right = $m->bind(fn($x) => $f($x)->bind($g));

        expect($left)->toBeInstanceOf(Left::class);
        expect($right)->toBeInstanceOf(Left::class);
        expect($left->extract())->toBe('error');
        expect($right->extract())->toBe('error');
    });
});
