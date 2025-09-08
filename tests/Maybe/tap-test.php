<?php

use lray138\G2\Maybe;

// Test tap with Just
echo "Testing tap with Just:\n";
$maybe = Maybe::just("hello");
$result = $maybe->tap(function($value) {
    echo "Tapped value: $value\n";
});
echo "Result is Just: " . ($result->isJust() ? "true" : "false") . "\n";
echo "Result value: " . $result->get() . "\n\n";

// Test tap with Nothing
echo "Testing tap with Nothing:\n";
$maybe = Maybe::nothing();
$result = $maybe->tap(function($value) {
    echo "This should not be called\n";
});
echo "Result is Nothing: " . ($result->isNothing() ? "true" : "false") . "\n\n";

// Test tap with null value (Just containing null)
echo "Testing tap with Just containing null:\n";
$maybe = Maybe::justNull();
$result = $maybe->tap(function($value) {
    echo "Tapped value is null: " . (is_null($value) ? "true" : "false") . "\n";
});
echo "Result is Just: " . ($result->isJust() ? "true" : "false") . "\n";
echo "Result value is null: " . (is_null($result->get()) ? "true" : "false") . "\n\n";

// Test chaining tap with other methods
echo "Testing tap chaining:\n";
$maybe = Maybe::just("world");
$result = $maybe
    ->tap(function($value) { echo "First tap: $value\n"; })
    ->map(function($value) { return strtoupper($value); })
    ->tap(function($value) { echo "Second tap: $value\n"; });
echo "Final result: " . $result->get() . "\n"; 