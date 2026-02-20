<?php

use lray138\G2\Str;
use FunctionalPHP\FantasyLand\Semigroup;
use InvalidArgumentException;

it('concat combines two Str values', function () {
    $a = Str::of('Hello');
    $b = Str::of(' World');

    $c = $a->concat($b);

    expect($c)->toBeInstanceOf(Str::class)
        ->and($c->extract())->toBe('Hello World');
});

it('concat does not mutate the original Str', function () {
    $a = Str::of('Hello');
    $b = Str::of(' World');

    $a->concat($b);

    expect($a->extract())->toBe('Hello');
});

it('concat throws when given a different Semigroup type', function () {
    $a = Str::of('Hello');

    $other = new class implements Semigroup {
        public function concat(Semigroup $value): Semigroup
        {
            return $this;
        }
    };

    expect(fn () => $a->concat($other))
        ->toThrow(InvalidArgumentException::class, 'Str::concat expects a Str');
});

it('satisfies the Semigroup associativity law for Str', function () {
    $a = Str::of('Hello');
    $b = Str::of(' ');
    $c = Str::of('World');

    // (a <> b) <> c
    $left = $a->concat($b)->concat($c);

    // a <> (b <> c)
    $right = $a->concat($b->concat($c));

    expect($left->extract())->toBe($right->extract())
        ->and($left->extract())->toBe('Hello World');
});

it('satisfies associativity across multiple samples', function () {
    $cases = [
        ['a', 'b', 'c'],
        ['Hello', ' ', 'World'],
        ['', 'x', ''],
        ['🔥', '⚡', ''],
    ];

    foreach ($cases as [$x, $y, $z]) {
        $a = Str::of($x);
        $b = Str::of($y);
        $c = Str::of($z);

        $left  = $a->concat($b)->concat($c);
        $right = $a->concat($b->concat($c));

        expect($left->extract())->toBe($right->extract());
    }
});

it('satisfies the Monoid right identity law for Str', function () {
    $a = Str::of('Hello');
    $e = Str::mempty();

    $right = $a->concat($e); // a <> e

    expect($right->extract())->toBe($a->extract());
});

it('satisfies the Monoid left identity law for Str', function () {
    $a = Str::of('Hello');
    $e = Str::mempty();

    $left = $e->concat($a); // e <> a

    expect($left->extract())->toBe($a->extract());
});