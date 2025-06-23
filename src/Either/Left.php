<?php

namespace lray138\G2\Either;

use lray138\G2\Either;
use FunctionalPHP\FantasyLand\{Apply, Semigroup};

final class Left extends Either implements Semigroup
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

    public function concat(Semigroup $s): Semigroup
    {
        return $this;
    }

    public function either(callable $f, $_)
    {
        return $f();
    }

    public function __call($method, $args) {
        return $this;
    }

    public function fold(callable $f, callable $_)
    {
        return $f($this->extract());
    }

    public function getOrLeft() {
        return $this;
    }
}