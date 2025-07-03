<?php

use lray138\G2\Either;
use lray138\G2\Either\Left;
use lray138\G2\Either\Right;

it('creates a Right and a Left correctly', function () {
    $right = Either::right(42);
    $left = Either::left('error');

    expect($right)->toBeInstanceOf(Right::class);
    expect($right->extract())->toBe(42);

    expect($left)->toBeInstanceOf(Left::class);
    expect($left->extract())->toBe('error');
});

it('map applies function to Right but not Left', function () {
    $right = Either::right(10)->map(fn($x) => $x * 2);
    $left = Either::left('fail')->map(fn($x) => $x * 2);

    expect($right)->toBeInstanceOf(Right::class);
    expect($right->extract())->toBe(20);

    expect($left)->toBeInstanceOf(Left::class);
    expect($left->extract())->toBe('fail');
});

it('bind applies function to Right but not Left', function () {
    $right = Either::right(5)->bind(fn($x) => Either::right($x + 3));
    $left = Either::left('fail')->bind(fn($x) => Either::right($x + 3));

    expect($right)->toBeInstanceOf(Right::class);
    expect($right->extract())->toBe(8);

    expect($left)->toBeInstanceOf(Left::class);
    expect($left->extract())->toBe('fail');
}); 

// test function "getOrElse" and "goe"

it('getOrElse returns the value of Right or the default value of Left', function () {
    $right = Either::right(42);
    $left = Either::left('error');

    expect($right->getOrElse(100))->toBe(42);
    expect($left->getOrElse(100))->toBe(100);
});

