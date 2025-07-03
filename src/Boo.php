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
        return new Boo(true, $operation);
    }

    public static function false($operation = "and")
    {
        return new Boo(false, $operation);
    }

    
    /**
     * Creates a new Boo instance from a value.
     * 
     * @param mixed $value The value to convert to boolean
     * @param string $operation The logical operation ("and" or "or")
     * @return static
     * @throws \InvalidArgumentException If value is not a boolean or integer 0/1
     * 
     * @see tests/Boo/original-tests.php for test coverage
     */
    public static function of($value, $operation = "and")
    {
        if (!is_bool($value) && !in_array($value, [0, 1], true)) {
            throw new \InvalidArgumentException('Boo::of expects a boolean or integer 0/1');
        }

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

    public function isFalse() {
        return $this->extract() == false;
    }

    public function isTrue() {
        return $this->extract() == true;
    }
    
    public function get()
    {
        return $this->extract();
    }

    public function extract()
    {
        return $this->value;
    }

    public function not(): self
    {
        // TODO: Implement
    }

    public function equals($other): Boo
    {
        // TODO: Implement
    }

}
