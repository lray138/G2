<?php 

use lray138\G2\Kvm;

it('returns "raw" returns raw php value (or null when missing)', function() {

    $kvm = Kvm::of([
        'a' => 1,
        'b' => '2',
        'c' => false,
        'd' => null,
        'e' => 0,
        'f' => '',
    ]);

    expect($kvm->raw('a'))->toBe(1);
    expect($kvm->raw('b'))->toBe('2');
    expect($kvm->raw('c'))->toBe(false);

    // key exists, value is null (array_key_exists should treat as present)
    expect($kvm->raw('d'))->toBe(null);

    // falsy values should come back as-is
    expect($kvm->raw('e'))->toBe(0);
    expect($kvm->raw('f'))->toBe('');

    // missing keys => null
    expect($kvm->raw('asdf'))->toBe(null);
    expect($kvm->raw('z'))->toBe(null);
});
