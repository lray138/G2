<?php 

use lray138\G2\{
    Lst,
    Maybe\Just,
    Maybe\Nothing
};

it('returns Just(Lst) for the tail of a non-empty list', function () {
    $lst = Lst::of([10, 20, 30]);
    $tail = $lst->mtail();

    expect($tail)->toBeInstanceOf(Just::class);
    expect($tail->extract()->get())->toEqual([20, 30]);
});

it('returns Nothing when mtail is called on a 1-element list', function () {
    $lst = Lst::of(['only']);
    $tail = $lst->mtail();

    expect($tail)->toBeInstanceOf(Nothing::class);
});

it('returns Nothing when mtail is called on an empty list', function () {
    $lst = Lst::mempty();
    $tail = $lst->mtail();

    expect($tail)->toBeInstanceOf(Nothing::class);
});

it('does not mutate the original Lst', function () {
    $lst = Lst::of([1, 2, 3]);
    $tail = $lst->mtail();

    expect($lst->get())->toEqual([1, 2, 3]);
    expect($tail->extract()->get())->toEqual([2, 3]);
});

it('returns Just(Lst) with a 1-element list when called on a 2-element list', function () {
    $lst = Lst::of([10, 20]);
    $tail = $lst->mtail();

    expect($tail)->toBeInstanceOf(Just::class);
    expect($tail->extract()->get())->toEqual([20]);
});

it('mtail does not treat falsy values as empty', function () {
    $lst = Lst::of([0, false, null, "yo"]);
    $tail = $lst->mtail();

    expect($tail)->toBeInstanceOf(Just::class);
    expect($tail->extract()->get())->toEqual([false, null, "yo"]);
});
