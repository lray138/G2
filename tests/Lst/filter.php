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

it('filters out falsy values using filterTruthy', function () {
    $lst = Lst::of([1, 0, 2, false, 3, '', 4, null, 5]);
    
    $filtered = $lst->filterTruthy();
    
    expect($filtered)->toBeInstanceOf(Lst::class);
    expect($filtered->get())->toBe([1, 2, 3, 4, 5]);
});

it('filters out empty strings and zeros using filterTruthy', function () {
    $lst = Lst::of(['hello', '', 'world', 0, 42, false, 'test']);
    
    $filtered = $lst->filterTruthy();
    
    expect($filtered->get())->toBe(['hello', 'world', 42, 'test']);
});

it('returns empty list when all values are falsy using filterTruthy', function () {
    $lst = Lst::of([0, false, '', null, []]);
    
    $filtered = $lst->filterTruthy();
    
    expect($filtered->get())->toBe([]);
});

it('preserves truthy values using filterTruthy', function () {
    $obj = new stdClass();
    $lst = Lst::of([1, 'hello', true, [1, 2, 3], $obj]);
    
    $filtered = $lst->filterTruthy();
    
    expect($filtered->get())->toBe([1, 'hello', true, [1, 2, 3], $obj]);
});
