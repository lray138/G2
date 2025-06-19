<?php 

use lray138\G2\Lst;

it('filters elements correctly based on predicate', function () {
    $lst = Lst::of([1, 2, 3, 4, 5]);

    $filtered = $lst->filter(fn($x) => $x % 2 === 0);

    expect($filtered)->toBeInstanceOf(Lst::class);
    // expect($filtered->get())->toEqual([2, 4]);
    expect($filtered->get())->toBe([2, 4]);
});

it('returns empty list if no elements match predicate', function () {
    $lst = Lst::of([1, 3, 5]);

    $filtered = $lst->filter(fn($x) => $x % 2 === 0);

    expect($filtered)->toBeInstanceOf(Lst::class);
    expect($filtered->get())->toEqual([]);
});

it('returns the same list if all elements match predicate', function () {
    $lst = Lst::of([2, 4, 6]);

    $filtered = $lst->filter(fn($x) => $x % 2 === 0);

    // expect($filtered->get())->toEqual([2, 4, 6]);
    expect($filtered->get())->toBe([2, 4, 6]);
});
