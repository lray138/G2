<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\{Monoid, Semigroup};
use lray138\G2\Either;
use lray138\G2\Common\Tappable;
use function lray138\G2\dump;

class Tree implements Monoid
{
    private $value;
    private $children;

    private function __construct($value, array $children = [])
    {
        $this->value = $value;
        $this->children = $children;
    }

    public function dump() {
        dump($this);
        return $this;
    }

    public function die($message = "") {
        die($message);
    }

    public static function of($value, array $children = [])
    {
        if (is_null($value)) {
            throw new \Exception("Tree::of() requires a valid value");
        }

        return new static($value, $children);
    }

    public static function leaf($value)
    {
        return new static($value, []);
    }

    public static function node($value, array $children = [])
    {
        return new static($value, $children);
    }

    public static function fromArray(array $data, $valueKey = 'value', $childrenKey = 'children')
    {
        if (!isset($data[$valueKey])) {
            throw new \Exception("Tree::fromArray() requires a '{$valueKey}' key");
        }

        $children = [];
        if (isset($data[$childrenKey]) && is_array($data[$childrenKey])) {
            foreach ($data[$childrenKey] as $childData) {
                $children[] = self::fromArray($childData, $valueKey, $childrenKey);
            }
        }

        return new static($data[$valueKey], $children);
    }

    public static function mempty()
    {
        return new static(null, []);
    }

    use Tappable;

    public function map(callable $fn): self
    {
        $newValue = $fn($this->value);
        $newChildren = array_map(function($child) use ($fn) {
            return $child->map($fn);
        }, $this->children);

        return new static($newValue, $newChildren);
    }

    public function bind(callable $fn): self
    {
        $result = $fn($this->value);
        
        if (!$result instanceof self) {
            throw new \Exception("Tree::bind() callback must return a Tree");
        }

        $newChildren = array_map(function($child) use ($fn) {
            return $child->bind($fn);
        }, $this->children);

        return new static($result->value, array_merge($result->children, $newChildren));
    }

    public function flatMap(callable $fn): self
    {
        return $this->bind($fn);
    }

    public function filter(callable $predicate): self
    {
        if (!$predicate($this->value)) {
            return self::mempty();
        }

        $filteredChildren = array_filter(
            array_map(function($child) use ($predicate) {
                return $child->filter($predicate);
            }, $this->children),
            function($child) {
                return !$child->isEmpty();
            }
        );

        return new static($this->value, array_values($filteredChildren));
    }

    public function reduce(callable $fn, $initial)
    {
        $result = $fn($initial, $this->value);
        
        foreach ($this->children as $child) {
            $result = $child->reduce($fn, $result);
        }

        return $result;
    }

    public function fold(callable $fn, $initial)
    {
        return $this->reduce($fn, $initial);
    }

    public function traverse(callable $fn): self
    {
        $newValue = $fn($this->value);
        $newChildren = array_map(function($child) use ($fn) {
            return $child->traverse($fn);
        }, $this->children);

        return new static($newValue, $newChildren);
    }

    public function addChild(self $child): self
    {
        return new static($this->value, array_merge($this->children, [$child]));
    }

    public function addChildren(array $children): self
    {
        $validChildren = array_filter($children, function($child) {
            return $child instanceof self;
        });

        return new static($this->value, array_merge($this->children, $validChildren));
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function isLeaf(): bool
    {
        return empty($this->children);
    }

    public function isNode(): bool
    {
        return !empty($this->children);
    }

    public function isEmpty(): bool
    {
        return is_null($this->value) && empty($this->children);
    }

    public function size(): int
    {
        return 1 + array_reduce($this->children, function($acc, $child) {
            return $acc + $child->size();
        }, 0);
    }

    public function height(): int
    {
        if ($this->isLeaf()) {
            return 0;
        }

        return 1 + max(array_map(function($child) {
            return $child->height();
        }, $this->children));
    }

    public function depth(): int
    {
        return $this->height();
    }

    public function breadth(): int
    {
        if ($this->isLeaf()) {
            return 1;
        }

        return max(array_map(function($child) {
            return $child->breadth();
        }, $this->children));
    }

    public function find(callable $predicate): Either
    {
        if ($predicate($this->value)) {
            return Either::right($this);
        }

        foreach ($this->children as $child) {
            $result = $child->find($predicate);
            // Check if it's a Right using instanceof
            if ($result instanceof \lray138\G2\Either\Right) {
                return $result;
            }
        }

        return Either::left("Tree::find() - no node found matching predicate");
    }

    public function findValue($value): Either
    {
        return $this->find(function($nodeValue) use ($value) {
            return $nodeValue === $value;
        });
    }

    public function contains($value): bool
    {
        $result = $this->findValue($value);
        return $result instanceof \lray138\G2\Either\Right;
    }

    public function flatten(): array
    {
        $result = [$this->value];
        
        foreach ($this->children as $child) {
            $result = array_merge($result, $child->flatten());
        }

        return $result;
    }

    public function toArray($valueKey = 'value', $childrenKey = 'children'): array
    {
        $result = [$valueKey => $this->value];
        
        if (!empty($this->children)) {
            $result[$childrenKey] = array_map(function($child) use ($valueKey, $childrenKey) {
                return $child->toArray($valueKey, $childrenKey);
            }, $this->children);
        }

        return $result;
    }

    public function concat(Semigroup $a): Semigroup
    {
        if ($a instanceof self) {
            return new static($this->value, array_merge($this->children, [$a]));
        }

        throw new \Exception('Tree::concat() expects another Tree');
    }

    public function extract()
    {
        return $this->value;
    }

    public function get()
    {
        return $this->extract();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->toStringHelper(0);
    }

    private function toStringHelper(int $depth): string
    {
        $indent = str_repeat('  ', $depth);
        $result = $indent . (string)$this->value . "\n";
        
        foreach ($this->children as $child) {
            $result .= $child->toStringHelper($depth + 1);
        }

        return $result;
    }
} 