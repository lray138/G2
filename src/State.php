<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\{Apply, Functor, Monad, Monoid};
use lray138\G2\Common\PointedTrait;

// this combines code from widmogrod and php-fp-state

class State implements Monad
{
    // I don't think I copied this one in, but ironic that I'm
    // now copying in the State code... this is a tip of the cap,
    // thank you widmogrod!

    private $value;

    private function __construct(callable $continuation)
    {
        $this->value = $continuation;
    }

    public static function of($value)
    {
        return new State(fn($state) => [$value, $state]);
    }

    public function ap(Apply $b): Apply
    {
        return $this->bind(function ($f) use ($b) {
            return $b->map($f);
        });
    }

    public function bind(callable $f): State
    {
        return new State(
            function ($state) use ($f): array {
                list ($value, $newState) = $this->run($state);

                return $f($value)->run($newState);
            }
        );
    }

    public static function get(): State
    {
        return new State(
            function ($state) {
                return [$state, $state];
            }
        );
    }

    public static function put($state): State
    {
        return State::modify(
            function ($_) use ($state) {
                return $state;
            }
        );
    }

    public static function modify(callable $f): State
    {
        return new State(
            function ($state) use ($f): array {
                return [null, $f($state)];
            }
        );
    }

    public function map(callable $f): State
    {
        return $this->bind(
            function ($x) use ($f) {
                return State::of($f($x));
            }
        );
    }

    public function run($initial)
    {
        return call_user_func($this->value, $initial);
    }
}
