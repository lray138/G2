<?php

namespace lray138\G2;

final class Nil
{
    private static ?self $instance = null;

    private function __construct() {}

    public static function unit(): self
    {
        return self::$instance ??= new self();
    }

    public function unwrap(): null
    {
        return null;
    }

    public function get(): mixed
    {
        return null;
    }

    public function __toString(): string
    {
        return '';
    }

    public function isNil(): bool
    {
        return true;
    }

    public function getOrElse(mixed $value): mixed
    {
        return $value;
    }

    public function getOrCall(callable $fn): mixed
    {
        return $fn();
    }

    public function map() {
        return $this;
    }

    public function bind() {
        return $this;
    }

    public function ap() {
        return $this;
    }

}