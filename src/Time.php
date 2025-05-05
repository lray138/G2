<?php

namespace lray138\G2;

use lray138\G2\{Either, Str, IO};
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

    // public function bind(callable $f)
    // {
    //     return $f($this->run());
    // }

    // public function bind(callable $f): static
    // {
    //     return new static(function () use ($f) {
    //         $result = $this->run();         // Lazily run the current monad
    //         $bound = $f($result);           // Apply function (should return another monad)
    //         return $bound instanceof self
    //         ? $bound->run()             // Lazily extract from the returned monad
    //         : $bound;                   // Fallback: raw value
    //     });
    // }


    public function bind(callable $fn): self
    {
        return new static(fn() => $fn($this->run())); // Lazy binding
    }

    public function format(string $format): static
    {
        return $this->map(fn(DateTime $dt) => Str::of($dt->format($format)));
    }

    public function withTimeZone(\DateTimeZone $tz): static
    {
        return $this->map(fn(DateTime $dt) => $dt->setTimezone($tz));
    }

    public function modify(string $modifier): static
    {
        return $this->map(fn(DateTime $dt) => $dt->modify($modifier));
    }

    // public function getDaysInMonthNum(): IO
    // {
    //     return $this->bind(fn($dt) => IO::of(fn() => Num::of($dt->format('t'))));
    // }

    public function getDaysInMonthNum()
    {
        // Lazily compute the number of days in the month
        return $this->bind(fn($dt) => Num::of($dt->format('t')));
    }

    public function run(): mixed
    {
        return ($this->action)();
    }

    public function extract(): mixed
    {
        return Either::left("Method 'extract' not available, use run");
    }

    public function get(): mixed
    {
        return Either::left("Method 'get' not available, use run");
    }
}
