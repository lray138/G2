<?php 

use lray138\G2\Lst;
use lray138\G2\Either;

it('returns Either::right when head is called on non-empty list', function () {
    $lst = Lst::of(['a', 'b', 'c']);

    $head = $lst->head();

    expect($head->extract())->toBe('a');
});

it('returns Either::left with message when head is called on empty list', function () {
    $lst = Lst::mempty();

    $head = $lst->head();

    expect($head)->toBeInstanceOf(Either::class);
    expect($head->extract())->toBe('Lst::head() failed — list is empty');
});
