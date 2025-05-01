<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\{Apply, Monad};

class IO implements Monad
{
    private $action;

    public function __construct(callable $action)
    {
        $this->action = $action;
    }

    public static function of(mixed $value): self
    {
        return new self(fn() => $value);
    }

    public function run(): mixed
    {
        return ($this->action)();
    }

    public function map(callable $fn): self
    {
        return new self(fn() => $fn(($this->action)()));
    }

    public function bind(callable $fn): self
    {
        return new self(fn() => $fn(($this->action)())->run());
    }

    public function ap(Apply $ioValue): self
    {
        return new self(fn() => ($this->action)()($ioValue->run()));
    }
}
