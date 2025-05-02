<?php

namespace lray138\G2;

use lray138\G2\Arr;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use FunctionalPHP\FantasyLand\{Apply, Monad, Functor};
use DateTime;

class Time
{
    private \Closure $action;

    private function __construct(callable $action)
    {
        $this->action = $action(...);
    }

    public static function now(?\DateTimeZone $tz = null): static
    {
        return new static(fn() => new \DateTime('now', $tz));
    }

    public static function of(DateTime|string $value, ?\DateTimeZone $tz = null): static
    {
        return new static(fn() => self::parse($value, $tz));
    }

    private static function parse(DateTime|string $value, ?\DateTimeZone $tz = null): DateTime
    {
        if ($value instanceof DateTime) {
            return $tz ? (clone $value)->setTimezone($tz) : clone $value;
        }

        return match (true) {
            preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) =>
                DateTime::createFromFormat('Y-m-d', $value, $tz),
            preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value) =>
                DateTime::createFromFormat('m/d/Y', $value, $tz),
            preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value) =>
                DateTime::createFromFormat('Y-m-d H:i:s', $value, $tz),
            default => new DateTime($value, $tz),
        };
    }

    public function map(callable $f): static
    {
        return new static(fn() => $f($this->run()));
    }

    public function flatMap(callable $f): static
    {
        return $f($this->run());
    }

    public function format(string $format): static
    {
        return $this->map(fn(DateTime $dt) => $dt->format($format));
    }

    public function withTimeZone(\DateTimeZone $tz): static
    {
        return $this->map(fn(DateTime $dt) => $dt->setTimezone($tz));
    }

    public function modify(string $modifier): static
    {
        return $this->map(fn(DateTime $dt) => $dt->modify($modifier));
    }

    public function run(): mixed
    {
        return ($this->action)();
    }
}
