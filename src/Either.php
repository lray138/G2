<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\{Semigroup, Monad};
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
}
