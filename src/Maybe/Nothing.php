<?php

namespace lray138\G2\Maybe;

use lray138\G2\Maybe;
use FunctionalPHP\FantasyLand\Apply;

class Nothing extends Maybe
{
    public const of  = __CLASS__ . '::of';

    private function __construct()
    {
    }

    public static function of($value)
    {
        return new static();
    }

    public static function unit()
    {
        return new static();
    }

    public function ap(Apply $a): Apply
    {
        return $this;
    }

    public function map(callable $a): Nothing
    {
        return $this;
    }

    public function bind(callable $a)
    {
        return $this;
    }

    public function extract()
    {
    }

    public function fold(callable $nothing, callable $just)
    {
        return $nothing();
    }

    public function isNothing(): bool
    {
        return true;
    }

    public function isJust(): bool
    {
        return false;
    }

    public function getOrThrow(string $message = 'Attempted to get value from Nothing')
    {
        throw new \Exception($message);
    }

    public function getOrElse($default)
    {
        return $default;
    }

    public function get()
    {
        return null;
    }

    public function __toString() {
        return '';
    }

    public function tap(callable $fn): self
    {
        // Do nothing for Nothing, just return the Maybe unchanged
        return $this;
    }
}
