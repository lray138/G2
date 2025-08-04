<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\{Monoid, Semigroup};
use lray138\G2\Either;
use lray138\G2\Common\Tappable;
use function lray138\G2\dump;

class Lst implements Monoid
{
    private $value;

    private function __construct($data)
    {
        $this->value = $data;
    }

    public function dump() {
        dump($this);
        return $this;
    }

    public function die($message = "") {
        die($message);
    }

    public static function of($data)
    {
        if (is_null($data)) {
            return Either::left("Lst::class requires a valid value");
        }

        // Convert iterables (like DOMNodeList) to arrays early
        if (!is_array($data) && is_iterable($data)) {
            $data = iterator_to_array($data);
        }

        // If not iterable or array, wrap it in an array
        if (!is_array($data)) {
            $data = [$data];
        }

        if(is_array($data) && count($data) == 0) {
            return Lst::mempty();
        }

        // Ensure it's a list (zero-indexed, no keys)
        if (array_keys($data) !== range(0, count($data) - 1)) {
            return Either::left("Lst constructor expects a primitive array without keys");
        }

        return new static($data);
    }

    public function implode($glue = ''): Str
    {
        $glue = unwrap($glue);
        return Str::of(implode($glue, $this->extract()));
    }

    public static function mempty()
    {
        return new static([]);
    }

    use Tappable;

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
        if (empty($this->extract())) {
            return Either::left("Lst::head() failed — list is empty");
        }

        // reset() returns the first element value, or false if empty
        $first = reset($this->value);

        return $first !== false
            ? Either::right(wrap($first))
            : Either::left("Lst::head() failed — list is empty");
    }

    public function tail(): Either
    {
        $value = $this->extract();

        if (empty($value) || count($value) == 1) {
            return Either::left("Lst::tail() failed — list is empty");
        }

        $sliced = array_slice($this->value, 1);

        return Either::right(new static($sliced));
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

    public function push(...$items): self
    {
        return new static(array_merge($this->extract(), $items));
    }

function flatten() {
    $result = [];
    
    $flattenArray = function ($array, &$result) use (&$flattenArray) {
        foreach ($array as $key => $value) {
            $value = unwrap($value);
            if (is_array($value)) {
                $flattenArray($value, $result);
            } elseif ($value instanceof self) {
                $flattenArray($value->extract(), $result);
            } else {
                $result[] = $value;
            }
        }
    };

    $flattenArray($this->extract(), $result);

    return new static($result);
}

public function forEach(callable $callback): self
{
    foreach ($this->value as $key => $item) {
        $callback(wrap($item), wrap($key));
    }

    return $this;
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

    public function nth($index)
    {

        $index = unwrap($index);
        
        if ($index < 0) {
            return Left::of("Index must be non-negative");
        }

        if (count($this->extract()) == 0) {
            return Left::of("Index {$index} out of bounds");
        }

        if ($index === 0) {
            // head() returns Either, so just return it directly
            return $this->head();
        }

        return $this->tail()->bind(fn(Lst $t) => $t->nth($index - 1));
    }

    public function count(): Num {
        return Num::of(count($this->extract()));
    }

    public function last(): Either
    {
        // TODO: Implement
    }

    public function reverse(): self
    {
        // TODO: Implement
    }

    public function find(callable $predicate): Either
    {
        // TODO: Implement
    }

    public function findIndex(callable $predicate): Either
    {
        // TODO: Implement
    }

    public function every(callable $predicate): Boo
    {
        // TODO: Implement
    }

    public function some(callable $predicate): Boo
    {
        // TODO: Implement
    }

    public function sort(callable $comparator = null): self
    {
        // TODO: Implement
    }

    public function unique(): self
    {
        // TODO: Implement
    }

    public function take($n): self
    {
        // TODO: Implement
    }

    public function drop($n): self
    {
        // TODO: Implement
    }

    public function slice($start, $length = null): self
    {
        // TODO: Implement
    }

    public function join($separator = ''): Str
    {
        // TODO: Implement
    }

    public function isEmpty(): Boo
    {
        // TODO: Implement
    }

    public function zip($other): self
    {
        $a = $this->extract();
        if ($other instanceof self) {
            $b = $other->extract();
        } elseif (is_array($other)) {
            $b = $other;
        } else {
            throw new \InvalidArgumentException('zip expects a Lst or array');
        }
        $len = min(count($a), count($b));
        $zipped = [];
        for ($i = 0; $i < $len; $i++) {
            $zipped[] = [$a[$i], $b[$i]];
        }
        return new self($zipped);
    }
}
