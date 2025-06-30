<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\{Monoid, Semigroup};
use lray138\G2\Either;
use lray138\G2\Common\{
    GetPropTrait,
    GetPropsTrait 
};

use function lray138\G2\wrap;

class Kvm implements Monoid
{
    private $value;

    private function __construct($data)
    {
        $this->value = $data;
    }

    public static function of($data)
    {
        if (is_null($data)) {
            throw new \Exception("Kvm::of requires a valid associative array");
        }

        if (array_keys($data) == range(0, count($data) - 1)) {
            throw new \Exception("Kvm::of expects an associative array (key-value map)");
        }

        if (!is_array($data) && is_iterable($data)) {
            $data = iterator_to_array($data);
        } elseif (!is_array($data)) {
            $data = [$data];
        }

        return new static($data);
    }

    public static function either($data)
    {
        if (is_null($data)) {
            return Either::left("Kvm::of requires a valid associative array");
        }

        if(array_keys($data) == range(0, count($data) - 1)) {
            return Either::left("Kvm::of expects an associative array (key-value map)");
        }

        if (!is_array($data) && is_iterable($data)) {
            $data = iterator_to_array($data);
        } elseif (!is_array($data)) {
            $data = [$data];
        }

        return new static($data);
    }

    public static function mempty()
    {
        return new static([]);
    }

    public function concat(Semigroup $a): Semigroup
    {
        if ($a instanceof self) {
            return new static(array_merge($this->extract(), $a->extract()));
        }
        throw new \InvalidArgumentException('Kvm::concat expects a Kvm');
    }

    public function get()
    {
        return $this->extract();
    }

    public function extract()
    {
        return $this->value;
    }

    use GetPropTrait;
    use GetPropsTrait;

public function map(callable $fn): self
    {
        $mapped = [];
        foreach ($this->value as $key => $value) {
            $mapped[$key] = $fn($value, $key);
        }
        return new static($mapped);
    }

public function bind(callable $fn): self
{
    $bound = [];
    foreach ($this->value as $key => $value) {
        $result = $fn($value, $key);
        if (!$result instanceof self) {
            throw new \RuntimeException("Bind callback must return Kvm");
        }
        $bound[$key] = $result;
    }
    return new static($bound);
}

    public function filter(callable $fn): self
    {
        $result = [];
        foreach ($this->value as $key => $value) {
            if ($fn($value, $key)) {
                $result[$key] = $value;
            }
        }
        return new static($result);
    }

    public function set($key, $value) {
        $new = $this->extract();
        $new[$key] = $value;
        return new static($new);
    }

    public function reduce(callable $fn, $initial)
    {
        $acc = $initial;
        foreach ($this->value as $key => $value) {
            $acc = $fn($acc, $value, $key);
        }
        return $acc;
    }

    public function forEach(callable $fn): self {
        foreach ($this->extract() as $key => $value) {
            $fn(wrap($value), wrap($key));
        }
        return $this;
    }

    public function count() {
        return Num::of(count($this->extract()));
    }

}
