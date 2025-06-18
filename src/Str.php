<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\{Monoid, Semigroup, Pointed};
use lray138\G2\Either;

class Str implements Monoid, Pointed
{
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function of($s)
    {
        return new static($s);
    }

    public static function mempty()
    {
        return new static('');
    }

    public function concat(Semigroup $s): Semigroup
    {
        if ($s instanceof self) {
            return new static($this->extract() . $s->extract());
        }

        return Either::left('Str::class concat expets a Str');
    }

    public function map(callable $f): self
    {
        return new self($f($this->extract()));
    }

    public function bind(callable $f)
    {
        return $f($this->extract());
    }

    public function get()
    {
        return $this->extract();
    }

    public function extract()
    {
        return $this->value;
    }

    public function __toString() {
        return $this->extract();
    }
}
