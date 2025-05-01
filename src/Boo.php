<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\{Monoid, Semigroup};
use lray138\G2\Either;

class Boo implements Monoid
{
    private $value;
    private $operation;

    public function __construct($value, $operation = "and")
    {
        $this->value = (bool) $value;
        $this->operation = $operation;
    }

    public static function true($operation = "and")
    {
        return new Boolean(true, $operation);
    }

    public static function false($operation = "and")
    {
        return new Boolean(false, $operation);
    }

    public static function of($value, $operation = "and")
    {
        return new static($value, $operation);
    }

    public static function mempty($operation = null)
    {
        $operation = is_null($operation) ? "and" : $operation;
        $value = $operation === "and" ? true : false;
        return new self($value, $operation);
    }

    public function concat(Semigroup $other): Semigroup
    {
        if (!$other instanceof self) {
            return Either::left('Arr::class concat expects Str');
        }

        // Perform the combination based on the operation
        switch ($this->operation) {
            case "and":
                return new self($this->extract() && $other->extract(), "and");
            case "or":
                return new self($this->extract() || $other->extract(), "or");
            default:
                throw new \LogicException("Invalid operation: {$this->operation}");
        }
    }

    public function map(callable $f): self
    {
        return new self($f($this->extract()), $this->operation);
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
}
