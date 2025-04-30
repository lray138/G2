<?php

namespace lray138\G2\Either;

use lray138\G2\Either;
use FunctionalPHP\FantasyLand\Apply;

final class Left extends Either
{
    private $value;

    public static function of($value)
    {
        return new static($value);
    }

    private function __construct($value)
    {
        $this->value = $value;
    }

    public function ap(Apply $a): Apply
    {
        return $this;
    }

    public function map(callable $f): Left
    {
        return $this;
    }

    public function bind(callable $a): Left
    {
        return $this;
    }

    public function extract()
    {
        return $this->value;
    }
}
