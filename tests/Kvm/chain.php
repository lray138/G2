<?php 

use lray138\G2\Kvm;

it('binds over each key-value and returns a Kvm of Kvms', function () {
    $kvm = Kvm::of(['x' => 2, 'y' => 3]);

    $result = $kvm->bind(fn($val, $key) => Kvm::of([
        'original' => $val,
        'double' => $val * 2,
    ]));

    expect($result)->toBeInstanceOf(Kvm::class);

    $nested = $result->get();

    expect($nested)->toHaveKeys(['x', 'y']);
    expect($nested['x']->get())->toEqual(['original' => 2, 'double' => 4]);
    expect($nested['y']->get())->toEqual(['original' => 3, 'double' => 6]);

});
