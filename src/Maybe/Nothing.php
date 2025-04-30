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
        return null;
    }
}
