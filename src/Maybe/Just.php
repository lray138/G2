<?php

namespace lray138\G2\Maybe;

use lray138\G2\Maybe;
use FunctionalPHP\FantasyLand\Apply;

class Just extends Maybe
{
    protected $value;

    public const of  = __CLASS__ . '::of';

    private function __construct($value)
    {
        $this->value = $value;
    }

    public static function of($value)
    {
        return new static($value);
    }

    public function ap(Apply $val): Apply
    {
        return $val->map($this->extract());
    }

    public function bind(callable $f)
    {
        return $f($this->extract());
    }

    public function map(callable $f): Maybe
    {
        return parent::of($f($this->extract()));
    }

    public function extract()
    {
        return $this->value;
    }

    public function fold(callable $nothing, callable $just)
    {
        return $just($this->extract());
    }

    public function isNothing(): bool
    {
        return false;
    }

    public function isJust(): bool
    {
        return true;
    }

    public function getOrThrow(string $message = 'Attempted to get value from Nothing')
    {
        return $this->extract();
    }

    public function getOrElse($default)
    {
        return $this->extract();
    }

    public function get()
    {
        return $this->extract();
    }
}
