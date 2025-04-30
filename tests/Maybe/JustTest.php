<?php

use lray138\G2\Maybe\Just;
use lray138\G2\Maybe;

// it('throws an error if the constructor is accessed directly', function () {
//     // Trying to create a Just instance directly through the constructor should throw an error
//     expect(function () {
//         new Just(5);
//     })->toThrow(Error::class);
// });

// it('creates a Just instance with the given value', function () {
//     $value = 5;

//     // Create a Just instance using the `of` method
//     $maybe = Just::of($value);

//     // Ensure that the instance is of the correct type
//     expect($maybe)->toBeInstanceOf(Just::class);

//     // Ensure that the `Just` instance is a subclass of Maybe
//     expect($maybe)->toBeInstanceOf(Maybe::class);

//     expect($maybe->extract())->toBe(5);
//     // Ensure the value is correctly assigned (assuming a getter method or direct access to `$value`)
//     // expect($maybe->getValue())->toEqual($value);
// });

// it('creates', function() {
//     $t = Just::of;

//     expect($t(5))->toBeInstanceOf(Just::class);
// });