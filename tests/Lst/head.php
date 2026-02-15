<?php 

use lray138\G2\Lst;
use lray138\G2\Str;
use lray138\G2\Either;

it('returns Either::right when head is called on non-empty list', function () {
    $lst = Lst::of(['a', 1, false]);

    $head = $lst->head();

    expect($head)->toBeInstanceOf(Str::class);
});

it('returns null', function () {
    $lst = Lst::mempty();
    $head = $lst->head();
    expect($head)->toBe(null);
});

