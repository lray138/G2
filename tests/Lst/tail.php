<?php 

use lray138\G2\{
    Either\Left,
    Either\Right,
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
    expect($tail)->toBe(null);
});

it('returns an empty list when tail is called on an empty list', function () {
    $lst = Lst::mempty();
    $tail = $lst->tail();
    expect($tail)->toBe(null);
});

it('does not mutate the original list', function () {
    $lst = Lst::of([1, 2, 3]);
    $tail = $lst->tail();

    expect($lst->get())->toEqual([1, 2, 3]);
    expect($tail->get())->toEqual([2, 3]);
});

it('returns a 1-element list when called on a 2-element list', function () {
    $lst = Lst::of([10, 20]);
    $tail = $lst->tail();

    expect($tail)->toBeInstanceOf(Lst::class);
    expect($tail->get())->toEqual([20]);
});

it('tail does not treat falsy values as empty', function () {
    $lst = Lst::of([0, false, null, "yo"]);
    $tail = $lst->tail();

    expect($tail)->toBeInstanceOf(Lst::class);
    expect($tail->get())->toEqual([false, null, "yo"]);
});
