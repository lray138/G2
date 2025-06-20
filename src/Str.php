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

    public function replace($search, $replace) {

		$search = unwrap($search);
        $replace = unwrap($replace);

		return $this->map(fn($subject) 
            => str_replace($search, $replace, $subject)
        );
	}

    public function append($value) {
        return $this->map(fn(string $s) 
            => $s . unwrap($value)
        );
    }

    public function map(callable $f): self
    {
        $result = $f($this->extract());
        
        if (!is_string($result)) {
            throw new \RuntimeException("Lst::map must return an array wrapped in Lst");
        }

        return new self($f($this->extract()));
    }

    public function bind(callable $f): static
    {
        $result = $f($this->extract());

        if (!($result instanceof static)) {
            throw new \LogicException(sprintf(
                'bind() must return an instance of %s, got %s',
                static::class,
                is_object($result) ? get_class($result) : gettype($result)
            ));
        }

        return $result;
    }

    public function bindTo(callable $f) {
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

    use \lray138\G2\Common\ExtendTrait;

    public function __toString() {
        return $this->extract();
    }
}
