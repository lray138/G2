<?php 

use lray138\G2\Kvm;

it('maps over each value preserving keys', function () {
    $kvm = Kvm::of(['foo' => 1, 'bar' => 2, 'baz' => 3]);

    $mapped = $kvm->map(fn($value, $key) => $value * 2);

    expect($mapped)->toBeInstanceOf(Kvm::class);
    expect($mapped->get())->toEqual([
        'foo' => 2,
        'bar' => 4,
        'baz' => 6,
    ]);
});

it('maps values with keys available in callback', function () {
    $kvm = Kvm::of(['x' => 10, 'y' => 20]);

    $mapped = $kvm->map(fn($value, $key) => $key . ':' . $value);

    expect($mapped->get())->toEqual([
        'x' => 'x:10',
        'y' => 'y:20',
    ]);
});

it('returns an empty Kvm when mapping over an empty map', function () {
    $kvm = Kvm::mempty();

    $mapped = $kvm->map(fn($value) => $value * 10);

    expect($mapped)->toBeInstanceOf(Kvm::class);
    expect($mapped->get())->toEqual([]);
});