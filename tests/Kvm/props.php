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

it('returns "props" returns Either::right vs Either::left', function() {

    $kvm = Kvm::of([
        'a' => 1,
        'b' => '2',
        'c' => false
    ]);

    $ps = $kvm->props(['a', 'b']);
 
    expect($ps->prop('a')->get())->toBeInstanceOf(Num::class);

});