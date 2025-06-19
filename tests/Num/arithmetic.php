<?php 

use lray138\G2\Num;

it('adds primitive and Num values', function () {
    $a = Num::of(5);

    $result1 = $a->add(3);                // primitive
    $result2 = $a->add(Num::of(3));       // Num instance

    expect($result1->get())->toBe(8)
        ->and($result2->get())->toBe(8);
});

it('subtracts primitive and Num values', function () {
    $a = Num::of(10);

    $result1 = $a->subtract(4);
    $result2 = $a->subtract(Num::of(4));

    expect($result1->get())->toBe(6)
        ->and($result2->get())->toBe(6);
});

it('subtracts primitive and Num values using alias "sub"', function () {
    $a = Num::of(10);

    $result1 = $a->sub(4);
    $result2 = $a->sub(Num::of(4));

    expect($result1->get())->toBe(6)
        ->and($result2->get())->toBe(6);
});

it('multiplies by primitive and Num values', function () {
    $a = Num::of(6);

    $result1 = $a->multiply(7);
    $result2 = $a->multiply(Num::of(7));

    expect($result1->get())->toBe(42)
        ->and($result2->get())->toBe(42);
});

it('divides by primitive and Num values', function () {
    $a = Num::of(20);

    $result1 = $a->divide(4);
    $result2 = $a->divide(Num::of(4));

    expect($result1->get())->toBe(5)
        ->and($result2->get())->toBe(5);
});

it('calculates modulus with primitive and Num values', function () {
    $a = Num::of(10);

    $result1 = $a->mod(3);
    $result2 = $a->mod(Num::of(3));

    expect($result1->get())->toBe(1)
        ->and($result2->get())->toBe(1);
});

it('negates the value', function () {
    $a = Num::of(15);

    $result = $a->negate();

    expect($result->get())->toBe(-15);
});

