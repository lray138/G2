
<?php 

use lray138\G2\IO;

it('throws an error if the constructor is accessed directly', function () {
    // Trying to create a Just instance directly through the constructor should throw an error
    expect(function () {
        new Just(5);
    })->toThrow(Error::class);
});

describe("IO monad", function () {

    $unit = fn($x) => IO::of($x);

    it('satisfies the Left Identity law for IO monad', function () use ($unit) {
        $value = 5;
        $f = fn($x) => IO::of($x + 1);

        $left = $unit($value)->bind($f);
        $right = $f($value);

        expect($left->run())->toBe($right->run());
    });

    it('satisfies the Right Identity law for IO monad', function () use ($unit) {
        $m = IO::of(10);

        $left = $m->bind($unit);
        $right = $m;

        expect($left->run())->toBe($right->run());
    });

    it('satisfies the Associativity law for IO monad', function () use ($unit) {
        $m = IO::of(10);
        $f = fn($x) => IO::of($x + 1);
        $g = fn($x) => IO::of($x * 2);

        $left = $m->bind($f)->bind($g);
        $right = $m->bind(fn($x) => $f($x)->bind($g));

        expect($left->run())->toBe($right->run());
    });

    it('ap should apply a function to a value inside IO', function () {
        $fn = IO::of(fn($x) => $x * 2);
        $value = IO::of(5);

        $result = $fn->ap($value);

        expect($result->run())->toBe(10);
    });

    it('ap applied twice should chain the effects correctly', function () {
        $fn1 = IO::of(fn($x) => fn($y) => $x + $y); // Add 1
        $value = IO::of(5);
        $value2 = IO::of(7);

        // Apply the first function, then apply the second function
        $result = $fn1->ap($value)->ap($value2);

        expect($result->run())->toBe(12);  // (5 + 1) * 2 = 12
    });

});