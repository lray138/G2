<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\{Monoid, Semigroup};
use lray138\G2\Either;

class Lst implements Monoid
{
    private $value;

    private function __construct($data)
    {
        $this->value = $data;
    }

    public static function of($data)
    {
        if (is_null($data)) {
            return Either::left("Arr::class requires a valid value");
        }

        if(is_array($data) && count($data) == 0) {
            return new static($data);
        }

        if(array_keys($data) != range(0, count($data) - 1)) {
            return Either::left("List constructor expects a primative array without keys");
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

    public function map(callable $fn): self
    {
        return new static(array_map($fn, $this->value));
    }

    public function bind(callable $fn): self
    {
        $results = array_map($fn, $this->value); // results in array of Lst
        $flattened = [];

        foreach ($results as $item) {
            if ($item instanceof self) {
                $flattened = array_merge($flattened, $item->extract());
            } else {
                // Defensive: if bind didn't return Lst, you could throw or wrap
                $flattened[] = $item;
            }
        }

        return new static($flattened);
    }

    public function head(): Either
    {
        return isset($this->value[0])
            ? Either::right($this->value[0])
            : Either::left("Lst::head() failed — list is empty");
    }

    public function tail(): self
    {
        return new static(array_slice($this->value, 1));
    }

    public function filter(callable $predicate): self
    {
        return new static(array_values(
                array_filter($this->value, $predicate)
            )
        );
    }

    public function reduce(callable $fn, $initial)
    {
        return array_reduce($this->value, $fn, $initial);
    }

    public function concat(Semigroup $a): Semigroup
    {
        if ($a instanceof self) {
            return new static(array_merge($this->extract(), $a->extract()));
        }

        return Either::left('Arr::class concat expects Str');
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
