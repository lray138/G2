<?php

use lray138\G2\Maybe\Just;
use lray138\G2\Maybe;

it('throws an error if the constructor is accessed directly', function () {
    // Trying to create a Just instance directly through the constructor should throw an error
    expect(function () {
        new Just(5);
    })->toThrow(Error::class);
});