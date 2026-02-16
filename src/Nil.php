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

    public function value(): null
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
}
