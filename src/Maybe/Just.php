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

    public function ap(Apply $val): Apply {
        return $val->map($this->extract());
    }

    public function bind(callable $f) {
        return $f($this->extract());
    }

    public function map(callable $f): Maybe {
        return $this->bind($f)->extract();
    }

    public function extract() {
        return $this->value;
    }

}