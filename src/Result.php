<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\Monad;
use lray138\G2\Result\{Ok, Err};

abstract class Result implements Monad
{
    public static function of($value)
    {
        return is_null($value)
            ? Err::of('provided value was null')
            : Ok::of($value);
    }

    public static function ok($value)
    {
        return Ok::of($value);
    }

    public static function err($error)
    {
        return Err::of($error);
    }

    public static function try(callable $fn)
    {
        try {
            return Ok::of($fn());
        } catch (\Exception $e) {
            return Err::of($e->getMessage());
        }
    }
} 