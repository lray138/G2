<?php 

use function lray138\G2\wrap;

use lray138\G2\{
    Num,
    Str,
    Boo,
    Maybe\Nothing,
    Kvm,
    Lst
};

it('returns the Num type for "int" and "float"', function() {
    expect(wrap(1))->toBeInstanceOf(Num::class);
    expect(wrap(1.1))->toBeInstanceOf(Num::class);
});

it('returns the Str type for "string"', function() {
    expect(wrap('string'))->toBeInstanceOf(Str::class);
});

it('returns the Boo type for "boolean" values', function() {
    expect(wrap(true))->toBeInstanceOf(Boo::class);
});

it('returns the Nothing type for null values', function() {
    expect(wrap(null))->toBeInstanceOf(Nothing::class);
});

it('returns Kvm for an array with key / value pairs', function() {
    expect(wrap([
        'a' => 1,
        'b' => 2
    ]))->toBeInstanceOf(Kvm::class);
});

it('returns Lst for an indexed array', function() {
    expect(wrap([0,1]))->toBeInstanceOf(Lst::class);
});

// "isFunction" => "function",
// "isExpression" => "expression",
// "isResource" => "resource",
