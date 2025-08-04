<?php

namespace lray138\G2\Common;

use FunctionalPHP\FantasyLand\{Apply, Functor};

trait GonadTrait
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
        return $this->bind(fn($f) => $val->map($f));
    }

    public function bind(callable $f)
    {
        return $f($this->extract());
    }

    public function map(callable $f): Functor
    {
        return parent::of($f($this->extract()));
    }

    public function extract()
    {
        return $this->value;
    }

    public function get()
    {
        return $this->extract();
    }

    public function fmap(callable $f): Functor {
        return $this->map($f);
    }
    
}
