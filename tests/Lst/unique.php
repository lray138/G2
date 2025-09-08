<?php

use lray138\G2\Lst;

it('unique removes duplicate values from the list', function () {
    $list = Lst::of([1, 2, 2, 3, 3, 3, 4]);
    $result = $list->unique();
    
    expect($result->get())->toBe([1, 2, 3, 4]);
});

it('unique preserves order of first occurrence', function () {
    $list = Lst::of(['a', 'b', 'a', 'c', 'b', 'd']);
    $result = $list->unique();
    
    expect($result->get())->toBe(['a', 'b', 'c', 'd']);
});

it('unique works with empty list', function () {
    $list = Lst::of([]);
    $result = $list->unique();
    
    expect($result->get())->toBe([]);
});

it('unique works with single element', function () {
    $list = Lst::of([42]);
    $result = $list->unique();
    
    expect($result->get())->toBe([42]);
});

it('unique works with no duplicates', function () {
    $list = Lst::of([1, 2, 3, 4, 5]);
    $result = $list->unique();
    
    expect($result->get())->toBe([1, 2, 3, 4, 5]);
});

it('unique works with mixed types', function () {
    $list = Lst::of([1, '1', 2, '2', true, false, null]);
    $result = $list->unique();
    
    // Our custom implementation treats all values as unique based on serialization
    expect($result->get())->toBe([1, '1', 2, '2', true, false, null]);
});

it('unique works with objects', function () {
    $obj1 = new stdClass();
    $obj2 = new stdClass();
    $obj3 = $obj1; // Same reference
    
    $list = Lst::of([$obj1, $obj2, $obj3]);
    $result = $list->unique();
    
    // Our custom implementation uses spl_object_hash for objects
    // Same object references will be considered duplicates
    expect($result->get())->toBe([$obj1, $obj2]);
});
