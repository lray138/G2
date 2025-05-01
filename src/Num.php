<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\{Monoid, Semigroup, Pointed};
use lray138\G2\Either;

class Num implements Monoid, Pointed
{
    private $value;
    private $operation;

    private function __construct($value, $operation = null)
    {
        $this->value = $value;
        $this->operation = $operation ?? "add";
    }

    public static function of($value)
    {
        if (!is_numeric($value)) {
            return Either::left('Str::of expects a valid number');
        }

        if (is_string($value)) {
            $value = str_contains($value, '.') ? (float) $value : (int) $value;
        }

        return new static($value);
    }

    public static function prod($value = 1)
    {
        return new static($value, "mul");
    }

    public static function sum($value = 0)
    {
        return new static($value, "add");
    }

    public static function mempty($operation = null)
    {
        $operation = is_null($operation) ? "add" : $operation;
        $value = $operation === "add" ? 0 : 1;
        return new self($value, $operation);
    }

    public function concat(Semigroup $n): Semigroup
    {
        if ($this->operation === 'add') {
            return new self($this->extract() + $n->extract(), $this->operation);
        } elseif ($this->operation === 'mul') {
            return new self($this->extract() * $n->extract(), $this->operation);
        }

        throw new \Exception("Unknown operation"); // chatGPT
    }

    public function get()
    {
        return $this->extract();
    }

    public function extract()
    {
        return $this->value;
    }
}
