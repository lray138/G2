<?php 

use lray138\G2\Kvm;
use lray138\G2\Either\Left;

it('filters values correctly based on predicate', function () {
    $kvm = Kvm::of([
        'a' => 1,
        'b' => 2,
        'c' => 3,
        'd' => 4,
    ]);

    $filtered = $kvm->filter(fn($value, $key) => $value % 2 === 0);

    expect($filtered)->toBeInstanceOf(Kvm::class);
    expect($filtered->get())->toEqual([
        'b' => 2,
        'd' => 4,
    ]);
});

it('returns empty Kvm when nothing matches the predicate', function () {
    $kvm = Kvm::of([
        'x' => 1,
        'y' => 3,
        'z' => 5,
    ]);

    $filtered = $kvm->filter(fn($value) => $value % 2 === 0);

    expect($filtered->get())->toEqual([]);
});

it('preserves keys when filtering', function () {
    $kvm = Kvm::of([
        'a' => 10,
        'b' => 20,
        'c' => 30,
    ]);

    $filtered = $kvm->filter(fn($value, $key) => $key === 'b');

    expect($filtered->get())->toEqual(['b' => 20]);
});
