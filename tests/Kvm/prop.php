<?php 

use lray138\G2\{
    Either\Left,
    Either\Right,
    Kvm,
    Num,
    Str,
    Boo
};

use function lray138\G2\identity;

it('returns "prop" returns Either::right vs Either::left', function() {

    $kvm = Kvm::of([
        'a' => 1,
        'b' => '2',
        'c' => false
    ]);

    expect($kvm->prop('a'))->toBeInstanceOf(Right::class);
    expect($kvm->prop('z'))->toBeInstanceOf(Left::class);

    expect($kvm->prop('a')->get())->toBeInstanceOf(Num::class);
    expect($kvm->prop('b')->get())->toBeInstanceOf(Str::class);
    expect($kvm->prop('c')->get())->toBeInstanceOf(Boo::class);

});