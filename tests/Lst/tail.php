<?php 

use lray138\G2\{
    Either\Left,
    Lst
};

it('returns the tail of a non-empty list', function () {
    $lst = Lst::of([10, 20, 30]);

    $tail = $lst->tail();

    expect($tail)->toBeInstanceOf(Lst::class);
    expect($tail->get())->toEqual([20, 30]);
});

it('returns an empty list when tail is called on a 1-element list', function () {
    $lst = Lst::of(['only']);

    $tail = $lst->tail();

    expect($tail)->toBeInstanceOf(Lst::class);
    expect($tail->get())->toEqual([]);
});

it('returns an empty list when tail is called on an empty list', function () {
    $lst = Lst::mempty();

    $tail = $lst->tail();

    expect($tail)->toBeInstanceOf(Lst::class);
    expect($tail->get())->toEqual([]);
});
