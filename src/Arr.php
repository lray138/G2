<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\{Monoid, Semigroup};
use lray138\G2\Either;

class Arr implements Monoid
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
        if ($s instanceof self) {
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

    public function prop($key)
    {
        if (is_object($key) && method_exists($key, "extract")) {
            $key = $key->extract();
        }

        return isset($this->extract()[$key])
            ? Either::right($this->extract()[$key])
            : Either::left("prop '$key' not found");
    }
}
