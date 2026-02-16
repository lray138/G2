<?php 

use lray138\G2\{
    Either\Left,
    Either\Right,
    Kvm,
    Num,
    Str,
    Boo,
    Nil,
    Maybe\Just,
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
    expect($kvm->prop('d'))->toBeInstanceOf(Nil::class);
});

it('returns "mprop" returns appropriate type', function() {

    $kvm = Kvm::of([
        'a' => 1,
        'b' => '2',
        'c' => false
    ]);

    expect($kvm->mprop('a'))->toBeInstanceOf(Just::class);
    expect($kvm->mprop('a')->get())->toBeInstanceOf(Num::class);
    expect($kvm->mprop('b'))->toBeInstanceOf(Just::class);
    expect($kvm->mprop('b')->get())->toBeInstanceOf(Str::class);
    expect($kvm->mprop('c'))->toBeInstanceOf(Just::class);
    expect($kvm->mprop('c')->get())->toBeInstanceOf(Boo::class);
    expect($kvm->mprop('asdf'))->toBeInstanceOf(Nothing::class);
    expect($kvm->mprop('z'))->toBeInstanceOf(Nothing::class);

});

