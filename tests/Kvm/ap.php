<?php

use lray138\G2\Kvm;

it('ap applies functions to the whole Kvm', function () {
    $functions = Kvm::of([
        'double' => fn($kvm) => $kvm->map(fn($x) => $x * 2),
        'addTen' => fn($kvm) => $kvm->map(fn($x) => $x + 10)
    ]);
    
    $values = Kvm::of([
        'a' => 5,
        'b' => 10,
        'c' => 15
    ]);
    
    $result = $functions->ap($values);
    
    expect($result)->toBeInstanceOf(Kvm::class);
    $resultData = $result->get();
    expect($resultData['double']->get())->toBe(['a' => 10, 'b' => 20, 'c' => 30]);
    expect($resultData['addTen']->get())->toBe(['a' => 15, 'b' => 20, 'c' => 25]);
});

it('ap handles non-callable values gracefully', function () {
    $mixed = Kvm::of([
        'fn' => fn($kvm) => $kvm->map(fn($x) => $x * 2),
        'value' => 'not_callable'
    ]);
    
    $values = Kvm::of([
        'a' => 5,
        'b' => 10
    ]);
    
    $result = $mixed->ap($values);
    
    expect($result)->toBeInstanceOf(Kvm::class);
    $resultData = $result->get();
    expect($resultData['fn']->get())->toBe(['a' => 10, 'b' => 20]);
    expect($resultData['value'])->toBe('not_callable');
});

it('ap throws exception for non-Kvm parameter', function () {
    $kvm = Kvm::of(['a' => fn($x) => $x + 1]);
    
    // Create a mock object that implements Apply but is not Kvm
    $mockApply = new class implements \FunctionalPHP\FantasyLand\Apply, \FunctionalPHP\FantasyLand\Functor {
        public function ap(\FunctionalPHP\FantasyLand\Apply $val): \FunctionalPHP\FantasyLand\Apply {
            return $this;
        }
        
        public function map(callable $fn): \FunctionalPHP\FantasyLand\Functor {
            return $this;
        }
    };
    
    expect(fn() => $kvm->ap($mockApply))->toThrow(\InvalidArgumentException::class);
});

it('ap works with empty Kvms', function () {
    $empty = Kvm::of([]);
    $result = $empty->ap($empty);
    
    // Empty Kvm should return empty result
    expect($result->get())->toBe([]);
});

it('ap works with transformation functions', function () {
    $transforms = Kvm::of([
        'addTen' => fn($kvm) => $kvm->map(fn($x) => $x + 10),
        'square' => fn($kvm) => $kvm->map(fn($x) => $x * $x)
    ]);
    
    $values = Kvm::of([
        'x' => 5,
        'y' => 20
    ]);
    
    $result = $transforms->ap($values);
    
    expect($result)->toBeInstanceOf(Kvm::class);
    $resultData = $result->get();
    expect($resultData['addTen']->get())->toBe(['x' => 15, 'y' => 30]);
    expect($resultData['square']->get())->toBe(['x' => 25, 'y' => 400]);
});
