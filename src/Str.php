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
            => \lray138\G2\Types\isExpression($search) 
                ? preg_replace($search, $replace, $subject)
                : str_replace($search, $replace, $subject)
        );

		// return $this->map(fn($subject) 
        //     => str_replace($search, $replace, $subject)
        // );
	}

    use \lray138\G2\Common\Tappable;

    public function dump() {
        dump($this);
        return $this;
    }

    public function die($message = "") {
        die($message);
    }

    public function append($value) {
        return $this->map(fn(string $s) 
            => $s . unwrap($value)
        );
    }

    public function prepend($value) {
        return $this->map(fn(string $s) 
            => unwrap($value) . $s 
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

    public function explode($delimiter): Either {

        $delimiter = unwrap($delimiter);

        if ($delimiter === '') {
            return Either::left("Delimiter must not be empty");
        }

        if (strpos($this->extract(), $delimiter) === false) {
            return Either::left("Delimiter not found in string");
        }

        return Either::right(Lst::of(explode($delimiter, $this->extract())));
    }

    public function trim() {
		return new static(trim($this->extract()));
	}

    public function contains($needle): Boo {
        $n = unwrap($needle);
		return Boo::of(str_contains($this->extract(), $n));
    }

    public function wrap($a, $b) {
        $a = unwrap($a);
        $b = unwrap($b);
		return new self($a . $this->extract() . $b);
	}

    public function matchAll($regex, $flags = 0): Lst {

        $matches = [];
        preg_match_all($regex, $this->extract(), $matches, $flags);
        
        return Lst::of($matches);
    }

    public function beforeFirst($needle): Either {

        $pos = strpos($this->extract(), $needle);

        if ($pos === false) {
            return Either::left("Needle not found");
        }

        return Either::right(Str::of(substr($this->extract(), 0, $pos)));
    }

    public function afterFirst($needle): Either {
        $haystack = $this->extract();
        $needle = unwrap($needle);

        $pos = strpos($haystack, $needle);

        if ($pos === false) {
            return Either::left("Needle not found");
        }

        $start = $pos + strlen($needle);

        return Either::right(Str::of(substr($haystack, $start)));
    }

    public function toLowerCase() {
        return new static(strtolower($this->extract()));
    }
}