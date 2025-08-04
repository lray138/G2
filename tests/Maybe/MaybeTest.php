<?php

use lray138\G2\Maybe;

test('Maybe::nothing() represents absence of value', function() {
    $maybe = Maybe::nothing();
    
    expect($maybe->isNothing())->toBe(true);
    expect($maybe->isJust())->toBe(false);
    expect($maybe->getOrElse('default'))->toBe('default');
    expect($maybe->fold(
        fn() => 'absence',
        fn($value) => 'value: ' . $value
    ))->toBe('absence');
});

test('Maybe::just(null) represents a null value, not absence', function() {
    $maybe = Maybe::just(null);
    
    expect($maybe->isNothing())->toBe(false);
    expect($maybe->isJust())->toBe(true);
    expect($maybe->getOrElse('default'))->toBe(null);
    expect($maybe->fold(
        fn() => 'absence',
        fn($value) => 'value: ' . ($value === null ? 'null' : $value)
    ))->toBe('value: null');
});

test('Maybe::justNull() is an alias for Maybe::just(null)', function() {
    $maybe1 = Maybe::just(null);
    $maybe2 = Maybe::justNull();
    
    expect($maybe1->isJust())->toBe(true);
    expect($maybe2->isJust())->toBe(true);
    expect($maybe1->getOrElse('default'))->toBe(null);
    expect($maybe2->getOrElse('default'))->toBe(null);
});

test('Maybe::of(null) creates Nothing (absence)', function() {
    $maybe = Maybe::of(null);
    
    expect($maybe->isNothing())->toBe(true);
    expect($maybe->isJust())->toBe(false);
});

test('Maybe::of() with non-null value creates Just', function() {
    $maybe = Maybe::of('hello');
    
    expect($maybe->isNothing())->toBe(false);
    expect($maybe->isJust())->toBe(true);
    expect($maybe->getOrElse('default'))->toBe('hello');
});

test('getOrThrow throws exception for Nothing', function() {
    $maybe = Maybe::nothing();
    
    expect(fn() => $maybe->getOrThrow())->toThrow(Exception::class, 'Attempted to get value from Nothing');
    expect(fn() => $maybe->getOrThrow('Custom message'))->toThrow(Exception::class, 'Custom message');
});

test('getOrThrow returns value for Just', function() {
    $maybe = Maybe::just('hello');
    
    expect($maybe->getOrThrow())->toBe('hello');
    expect($maybe->getOrThrow('Custom message'))->toBe('hello');
});

test('get() method returns value for Just, null for Nothing', function() {
    $just = Maybe::just('hello');
    $nothing = Maybe::nothing();
    
    expect($just->get())->toBe('hello');
    expect($nothing->get())->toBe(null);
});

test('fold demonstrates the difference between absence and null', function() {
    $absence = Maybe::nothing();
    $nullValue = Maybe::just(null);
    
    $absenceResult = $absence->fold(
        fn() => 'No value exists',
        fn($value) => 'Value is: ' . $value
    );
    
    $nullResult = $nullValue->fold(
        fn() => 'No value exists',
        fn($value) => 'Value is: ' . ($value === null ? 'null' : $value)
    );
    
    expect($absenceResult)->toBe('No value exists');
    expect($nullResult)->toBe('Value is: null');
});

test('practical example: database lookup', function() {
    // Simulate database lookup that returns null when no record found
    function findUserById($id) {
        if ($id === 1) {
            return ['id' => 1, 'name' => 'John'];
        }
        return null; // No user found
    }
    
    // This represents absence (no user found)
    $user1 = Maybe::of(findUserById(999));
    expect($user1->isNothing())->toBe(true);
    expect($user1->getOrElse(['name' => 'Unknown']))->toBe(['name' => 'Unknown']);
    
    // This represents a user with null name
    $user2 = Maybe::just(['id' => 2, 'name' => null]);
    expect($user2->isJust())->toBe(true);
    expect($user2->getOrElse(['name' => 'Unknown']))->toBe(['id' => 2, 'name' => null]);
    
    // Extract the name, handling null as absence
    $name1 = $user1->fold(
        fn() => 'Unknown',
        fn($user) => $user['name'] ?? 'Unknown'
    );
    
    $name2 = $user2->fold(
        fn() => 'Unknown',
        fn($user) => $user['name'] ?? 'Unknown'
    );
    
    expect($name1)->toBe('Unknown');
    expect($name2)->toBe('Unknown');
});