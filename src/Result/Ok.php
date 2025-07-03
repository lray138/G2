<?php

namespace lray138\G2\Result;

use FunctionalPHP\FantasyLand\{Apply, Functor, Monad};
use lray138\G2\Result;
use lray138\G2\Kvm;

class Ok extends Result
{
    private $value;

    private function __construct($value)
    {
        $this->value = $value;
    }

    public static function of($value): self
    {
        return new self($value);
    }

    public function map(callable $f): Result
    {
        return Ok::of($f($this->value));
    }

    public function bind(callable $f): Result
    {
        return $f($this->value);
    }

    public function ap(Apply $result): Result
    {
        if ($result instanceof Ok) {
            return Ok::of($this->value($result->extract()));
        }
        return $result;
    }

    public function isOk(): bool
    {
        return true;
    }

    public function isErr(): bool
    {
        return false;
    }

    public function getOrElse($default)
    {
        return $this->value;
    }

    public function fold(callable $onOk, callable $onErr)
    {
        return $onOk($this->value);
    }

    public function mapErr(callable $f): Result
    {
        return $this;
    }

    public function extract()
    {
        return $this->value;
    }

    public function get()
    {
        return $this->extract();
    }

} 