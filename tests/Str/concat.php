<?php

use lray138\G2\Str;
use FunctionalPHP\FantasyLand\Semigroup;


describe('Str::concat', function () {
    it('combines two Str values', function () {
        $a = Str::of('Hello');
        $b = Str::of(' World');

        $c = $a->concat($b);

        expect($c)->toBeInstanceOf(Str::class)
            ->and($c->extract())->toBe('Hello World');
    });

    it('does not mutate the original Str', function () {
        $a = Str::of('Hello');
        $b = Str::of(' World');

        $a->concat($b);

        expect($a->extract())->toBe('Hello');
    });

    it('throws when given a different Semigroup type', function () {
        $a = Str::of('Hello');

        $other = new cla`ss implements Semigroup {
            public function concat(Semigroup $value): Semigroup
            {
                return $this;
            }
        };

        expect(fn () => $a->concat($other))
            ->toThrow(InvalidArgumentException::class, 'Str::concat expects a Str');
    });
})->group('str', 'concat');

describe('Str algebra laws', function () {
    // Semigroup law: associativity
    it('satisfies associativity', function () {
        $a = Str::of('Hello');
        $b = Str::of(' ');
        $c = Str::of('World');

        $left  = $a->concat($b)->concat($c);     // (a <> b) <> c
        $right = $a->concat($b->concat($c));     // a <> (b <> c)

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

    // Monoid laws: identity (requires mempty)
    it('satisfies right identity (a <> mempty = a)', function () {
        $a = Str::of('Hello');
        $e = Str::mempty();

        expect($a->concat($e)->extract())->toBe($a->extract());
    });

    it('satisfies left identity (mempty <> a = a)', function () {
        $a = Str::of('Hello');
        $e = Str::mempty();

        expect($e->concat($a)->extract())->toBe($a->extract());
    });
})->group('str', 'laws', 'algebra');