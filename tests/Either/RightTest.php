<?php

use lray138\G2\Either\Right;
use lray138\G2\Str;

it('throws an error if the constructor is accessed directly', function () {
    // Trying to create a Right instance directly through the constructor should throw an error
    expect(function () {
        new Right(5);
    })->toThrow(Error::class);
});


describeMonad(lray138\G2\Either\Right::class);

$prop = fn($key) => fn($store) => $store["$key"];

it('returns a stored property using these methods', function() use($prop) {
    
    expect(
        Right::of(['kvm' => 'value'])
            ->map($prop('kvm'))
            ->get()
    )->toBe('value');

});