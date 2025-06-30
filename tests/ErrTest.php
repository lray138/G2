<?php 

use lray138\G2\{
    Err,
    Either
};

it('constructs correctly', function () {
    $e = Err::of("This is a simple message");

    expect($e)->toBeInstanceOf(Err::class);
});

