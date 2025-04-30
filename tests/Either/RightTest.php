<?php

use lray138\G2\Either\Right;

it('throws an error if the constructor is accessed directly', function () {
    // Trying to create a Right instance directly through the constructor should throw an error
    expect(function () {
        new Right(5);
    })->toThrow(Error::class);
});

describeMonad(lray138\G2\Either\Right::class);