<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\Monad;
use lray138\G2\Maybe\{Nothing, Just};

abstract class Maybe implements Monad
{
    public static function of($value)
    {
        return is_null($value)
            ? Nothing::unit()
            : Just::of($value);
    }

    public static function just($value)
    {
        return Just::of($value);
    }

    public static function nothing()
    {
        return Nothing::unit();
    }

    /**
     * Create a Maybe containing null as a value (not absence)
     */
    public static function justNull()
    {
        return Just::of(null);
    }

    /**
     * Check if this Maybe represents absence (Nothing)
     */
    abstract public function isNothing(): bool;

    /**
     * Check if this Maybe contains a value (Just)
     */
    abstract public function isJust(): bool;

    /**
     * Get the contained value or throw an exception if Nothing
     */
    abstract public function getOrThrow(string $message = 'Attempted to get value from Nothing');

    /**
     * Get the contained value or return default if Nothing
     */
    abstract public function getOrElse($default);

    /**
     * Get the contained value (returns null for Nothing)
     */
    abstract public function get();

    /**
     * Fold (catamorphism) - extract a value by providing callbacks for both cases
     * 
     * @param callable $nothing Case for when Maybe is Nothing
     * @param callable $just Case for when Maybe is Just
     * @return mixed
     */
    abstract public function fold(callable $nothing, callable $just);

    /**
     * Tap - execute a callback function on the contained value (if it exists) and return the Maybe unchanged
     * 
     * @param callable $fn Callback function to execute
     * @return self
     */
    abstract public function tap(callable $fn): self;
}
