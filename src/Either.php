<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\{Monad};
use lray138\G2\Either\{Left, Right};

abstract class Either implements Monad
{
    public static function of($value)
    {
        return is_null($value)
            ? Left::of('provided value was null')
            : Right::of($value);
    }

    public static function left($message)
    {
        return Left::of($message);
    }

    public static function right($value)
    {
        return Right::of($value);
    }

    public function goe($value) {
        return $this instanceof Left 
            ? $value
            : $this->extract();

    }

    /**
     * Map over the contained value (only applies to Right)
     * 
     * @param callable $f Function to apply to the value
     * @return Either
     */
    abstract public function map(callable $f): Either;

    /**
     * Bind (flatMap) - chain computations that may fail
     * 
     * @param callable $f Function that returns an Either
     * @return Either
     */
    abstract public function bind(callable $f): Either;

    /**
     * Extract the contained value
     * 
     * @return mixed
     */
    abstract public function extract();

}
