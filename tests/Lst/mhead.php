<?php 

use lray138\G2\Lst;
use lray138\G2\Str;
use lray138\G2\Either;
use lray138\G2\Maybe\Just;
use lray138\G2\Maybe\Nothing;

it('returns Just when head is called on non-empty list', function () {
    $lst = Lst::of(['a', 1, false]);
    $head = $lst->mhead();
    expect($head)->toBeInstanceOf(Just::class);
    expect($head->get())->toBeInstanceOf(Str::class);
    expect($head->get()->get())->toBe('a');
});

it('returns Nothing on an empty list', function () {
    $lst = Lst::mempty();
    $head = $lst->mhead();
    expect($head)->toBeInstanceOf(Nothing::class);
    expect($head->get())->toBe(null);
});