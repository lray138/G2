<?php

use lray138\G2\Boo;

it('satisfies the Left Identity law for Boo', function () {
    $value = true;
    $f = fn($x) => Boo::of(!$x);

    $bound = Boo::of($value)->bind($f);
    $direct = $f($value);

    expect($bound->extract())->toBe($direct->extract());
});

it('satisfies the Right Identity law for Boo', function () {
    $m = Boo::of(false);
    $bound = $m->bind(fn($x) => Boo::of($x));

    expect($bound->extract())->toBe($m->extract());
});

it('satisfies the Associativity law for Boo', function () {
    $m = Boo::of(true);
    $f = fn($x) => Boo::of(!$x);
    $g = fn($x) => Boo::of($x && true);

    $left = $m->bind($f)->bind($g);
    $right = $m->bind(fn($x) => $f($x)->bind($g));

    expect($left->extract())->toBe($right->extract());
});

it('satisfies the Map Law for Boo', function () {
    $value = false;
    $f = fn($x) => !$x;

    $mapped = Boo::of($value)->map($f);
    $expected = Boo::of($f($value));

    expect($mapped->extract())->toBe($expected->extract());
});

it('ensures map is consistent with bind for Boo', function () {
    $f = fn($x) => !$x;
    $m = Boo::of(true);

    $mapResult = $m->map($f);
    $bindResult = $m->bind(fn($x) => Boo::of($f($x)));

    expect($mapResult->extract())->toBe($bindResult->extract());
});
