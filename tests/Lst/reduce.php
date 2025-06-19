<?php 

use lray138\G2\Lst;

it('reduces the list by summing all elements', function () {
    $lst = Lst::of([1, 2, 3, 4]);

    $sum = $lst->reduce(fn($acc, $x) => $acc + $x, 0);

    expect($sum)->toBe(10);
});

it('reduces the list by concatenating strings', function () {
    $lst = Lst::of(['a', 'b', 'c']);

    $result = $lst->reduce(fn($acc, $x) => $acc . $x, '');

    expect($result)->toBe('abc');
});

it('returns the initial value when reducing an empty list', function () {
    $lst = Lst::mempty();

    $result = $lst->reduce(fn($acc, $x) => $acc + $x, 100);

    expect($result)->toBe(100);
});
