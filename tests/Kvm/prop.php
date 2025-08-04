<?php 

use lray138\G2\{
    Either\Left,
    Either\Right,
    Kvm,
    Num,
    Str,
    Boo,
    Maybe\Nothing
};

use function lray138\G2\identity;

it('returns "prop" returns appropriate type', function() {

    $kvm = Kvm::of([
        'a' => 1,
        'b' => '2',
        'c' => false
    ]);

    expect($kvm->prop('a'))->toBeInstanceOf(Num::class);
    expect($kvm->prop('b'))->toBeInstanceOf(Str::class);
    expect($kvm->prop('c'))->toBeInstanceOf(Boo::class);
    expect($kvm->prop('asdf'))->toBeInstanceOf(Nothing::class);
    expect($kvm->prop('z')->get())->toBe(null);

});