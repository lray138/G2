<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\{Apply, Monad, Monoid};
use lray138\G2\Str;

class Reader implements Monad
{
    private $action;

    private function __construct(callable $action)
    {
        $this->action = $action;
    }

    public static function of($x): Reader
    {
        return new Reader(
            function ($_) use ($x) {
                return $x;
            }
        );
    }

    public static function ask(): Reader
    {
        return new Reader(
            function ($x) {
                return $x;
            }
        );
    }

    public function bind(callable $f): Reader
    {
        return new Reader(
            function ($env) use ($f) {
                return $f($this->run($env))->run($env);
            }
        );
    }

    public function map(callable $f): Reader
    {
        return $this->bind(
            function ($a) use ($f) {
                return Reader::of($f($a));
            }
        );
    }

    public function ap(Apply $x): Reader
    {
        return $this->bind(
            function ($f) use ($x) {
                return $x->map($f);
            }
        );
    }

    public function run($env)
    {
        return call_user_func($this->action, $env);
    }
}
