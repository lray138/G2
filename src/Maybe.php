<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\Monad;
use lray138\G2\Maybe\{Nothing, Just};

abstract class Maybe implements Monad
{
    public static function of($value)
    {
        return is_null($value)
            ? Nothing::unit()
            : Just::of($value);
    }
}
