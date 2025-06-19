<?php 

use lray138\G2\Kvm;
use lray138\G2\Either\Left;

it('reduces values to a single sum', function () {
    $kvm = Kvm::of([
        'a' => 1,
        'b' => 2,
        'c' => 3,
    ]);

    $result = $kvm->reduce(fn($acc, $value) => $acc + $value, 0);

    expect($result)->toBe(6);
});

it('reduces using both value and key', function () {
    $kvm = Kvm::of([
        'x' => 10,
        'y' => 20,
    ]);

    $result = $kvm->reduce(function ($acc, $value, $key) {
        return [...$acc, "$key:$value"];
    }, []);

    expect($result)->toEqual(['x:10', 'y:20']);
});

it('returns initial value when reducing an empty map', function () {
    $kvm = Kvm::mempty();

    $result = $kvm->reduce(fn($acc, $value) => $acc + $value, 100);

    expect($result)->toBe(100);
});
