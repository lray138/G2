<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\{Monoid, Semigroup, Pointed};
use lray138\G2\Either\Left;

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
            return Either::left('Num::of expects a valid number');
        }

        if (is_string($value)) {
            $value = str_contains($value, '.') ? (float) $value : (int) $value;
        }

        return new static($value);
    }

    // 
    public static function prod($value = 1)
    {
        return new static($value, "mul");
    }

    // wow, I wonder what tangent I was on when this was added ;)
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
            return new self($this->value + $n->extract(), $this->operation);
        } elseif ($this->operation === 'mul') {
            return new self($this->value * $n->extract(), $this->operation);
        }

        return Left::of("Unknown operation");
    }

    // ───────────────────────────────
    // Arithmetic
    public function add($n): self
    {
        return new self($this->value + unwrap($n));
    }
    public function subtract($n): self
    {
        return new self($this->value - unwrap($n));
    }
    public function sub($n): self
    {
        return $this->subtract($n);
    }
    public function multiply($n): self
    {
        return new self($this->value * unwrap($n));
    }
    public function divide($n): self
    {
        return new self($this->value / unwrap($n));
    }
    public function mod($n): self
    {
        return new self($this->value % unwrap($n));
    }
    public function negate(): self
    {
        return new self(-$this->value);
    }

    // Rounding
    public function round(int $precision = 0): self
    {
        return new self(round($this->value, $precision));
    }
    public function floor(): self
    {
        return new self(floor($this->value));
    }
    public function ceil(): self
    {
        return new self(ceil($this->value));
    }
    public function truncate(): self
    {
        return new self((int) $this->value);
    }

    // Comparison
    public function equals($n): Boo
    {
        return Boo::of($this->value == unwrap($n));
    }
    public function greaterThan($n): Boo
    {
        return Boo::of($this->value > unwrap($n));
    }
    public function lessThan($n): Boo
    {
        return Boo::of($this->value < unwrap($n));
    }
    public function compareTo($n): self
    {
        return new self($this->value <=> unwrap($n));
    }

    // Utility
    public function isInt(): Boo
    {
        return Boo::of(is_int($this->value));
    }
    public function isFloat(): Boo
    {
        return Boo::of(is_float($this->value));
    }
    public function isEven(): Boo
    {
        return Boo::of($this->value % 2 === 0);
    }
    public function isOdd(): Boo
    {
        return Boo::of($this->value % 2 !== 0);
    }

    public function clamp($min, $max): self
    {
        return new self(max($min, min($this->value, $max)));
    }

    public function inRange($min, $max): Boo
    {
        return Boo::of($this->value >= $min && $this->value <= $max);
    }

    // Conversion
    public function toFloat(): self
    {
        return new self((float) $this->value);
    }

    public function toInt(): self
    {
        return new self((int) $this->value);
    }

    // Access
    public function get()
    {
        return $this->extract();
    }

    public function extract()
    {
        return $this->value;
    }

    public function __toString() {
        return (string) $this->extract();
    }

    public function abs(): self
    {
        // TODO: Implement
    }

    public function pow($exponent): self
    {
        // TODO: Implement
    }

    public function min($other): self
    {
        // TODO: Implement
    }

    public function max($other): self
    {
        // TODO: Implement
    }

    public function toStr(): Str
    {
        return Str::of($this->value);
    }
}
