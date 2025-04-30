<?php

use lray138\G2\Maybe\Just;

it('throws an error if the constructor is accessed directly', function () {
    // Trying to create a Just instance directly through the constructor should throw an error
    expect(function () {
        new Just(5);
    })->toThrow(Error::class);
});

describe('Just monad', function () {
    $value = 5;
    $f = fn($x) => $x + 1;
    $g = fn($x) => $x * 2;
    $unit = fn($x) => Just::of($x);

    it('satisfies the Left Identity law', function () use ($value, $f, $unit) {
        $result = $unit($value)->bind(fn($x) => $unit($f($x)));
        expect($result->extract())->toBe($f($value));
    });

    it('satisfies the Right Identity law', function () use ($value, $unit) {
        $m = $unit($value);
        $result = $m->bind($unit);
        expect($result->extract())->toBe($m->extract());
    });

    it('satisfies the Associativity law', function () use ($value, $unit, $f, $g) {
        $m = $unit($value);
        $fM = fn($x) => $unit($f($x));
        $gM = fn($x) => $unit($g($x));

        $left = $m->bind($fM)->bind($gM);
        $right = $m->bind(fn($x) => $fM($x)->bind($gM));

        expect($left->extract())->toBe($right->extract());
    });
});

// it('creates', function() {
//     $t = Just::of;

//     expect($t(5))->toBeInstanceOf(Just::class);
// });