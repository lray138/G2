<?php

namespace lray138\G2;

use DateTime;
use DateTimeZone;
use lray138\G2\{Either, Str, Num};

class Time
{
    private $value;

    public function __construct(DateTime $value)
    {
        $this->value = $value;
    }

    public function add(string|self $value)
    {
        $copy = clone $this->value;

        if (is_string($value)) {
            $copy->modify($value);
            return new static($copy);
        }

        if ($value instanceof self) {
            $interval = $this->value->diff($value->value);
            $copy->add($interval);
            return new static($copy);
        }

        return Either::left("add() expects a string or Time instance.");
    }

    public static function now(?DateTimeZone $tz = null): static
    {
        return new static(new DateTime('now', $tz));
    }

    public static function of(DateTime|string $value, ?DateTimeZone $tz = null): static
    {
        return new static(self::parse($value, $tz));
    }

    private static function parse(DateTime|string $value, ?DateTimeZone $tz = null): DateTime
    {
        if ($value instanceof DateTime) {
            return $tz ? (clone $value)->setTimezone($tz) : clone $value;
        }

        return match (true) {
            preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) =>
                DateTime::createFromFormat('Y-m-d', $value, $tz) ?: new DateTime($value, $tz),
            preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value) =>
                DateTime::createFromFormat('m/d/Y', $value, $tz) ?: new DateTime($value, $tz),
            preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value) =>
                DateTime::createFromFormat('Y-m-d H:i:s', $value, $tz) ?: new DateTime($value, $tz),
            default => new DateTime($value, $tz),
        };
    }

    public function map(callable $f): static
    {
        return new static($f(clone $this->value));
    }

    public function bind(callable $f): mixed
    {
        return $f(clone $this->value);
    }

    public function format(string $format): Str
    {
        return Str::of($this->value->format($format));
    }

    public function withTimeZone(DateTimeZone $tz): static
    {
        $copy = clone $this->value;
        $copy->setTimezone($tz);
        return new static($copy);
    }

    public function modify(string $modifier): static
    {
        $copy = clone $this->value;
        $copy->modify($modifier);
        return new static($copy);
    }

    public function getDaysInMonthNum(): Num
    {
        return Num::of((int) $this->value->format('t'));
    }


    public function extract(): DateTime
    {
        return $this->value;
    }

    public function get(): DateTime
    {
        return $this->extract();
    }

    public function diff(Time $other): \DateInterval
    {
        // TODO: Implement
    }

    public function isBefore(Time $other): Boo
    {
        // TODO: Implement
    }

    public function isAfter(Time $other): Boo
    {
        // TODO: Implement
    }

    public function isSame(Time $other): Boo
    {
        // TODO: Implement
    }

    public function toTimestamp(): Num
    {
        // TODO: Implement
    }

    public function getYear(): Num
    {
        // TODO: Implement
    }

    public function getMonth(): Num
    {
        // TODO: Implement
    }

    public function getDay(): Num
    {
        // TODO: Implement
    }

    public function getHour(): Num
    {
        // TODO: Implement
    }

    public function getMinute(): Num
    {
        // TODO: Implement
    }

    public function getSecond(): Num
    {
        // TODO: Implement
    }

    public function getDayOfWeek(): Num
    {
        // TODO: Implement
    }

    public function getDayOfYear(): Num
    {
        // TODO: Implement
    }

    public function isToday(): Boo
    {
        // TODO: Implement
    }

    public function isYesterday(): Boo
    {
        // TODO: Implement
    }

    public function isTomorrow(): Boo
    {
        // TODO: Implement
    }

    public function startOfDay(): static
    {
        // TODO: Implement
    }

    public function endOfDay(): static
    {
        // TODO: Implement
    }

    public function startOfWeek(): static
    {
        // TODO: Implement
    }

    public function endOfWeek(): static
    {
        // TODO: Implement
    }

    public function startOfMonth(): static
    {
        // TODO: Implement
    }

    public function endOfMonth(): static
    {
        // TODO: Implement
    }

    public function startOfYear(): static
    {
        // TODO: Implement
    }

    public function endOfYear(): static
    {
        // TODO: Implement
    }

    public function age(): Num
    {
        // TODO: Implement
    }
}
