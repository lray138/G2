<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\{Apply, Monad, Monoid};
use lray138\G2\Str;

class Writer implements Monad
{
    private $action;

    private function __construct(callable $action)
    {
        $this->action = $action;
    }

    public static function of($value, ?Monoid $log = null): Writer
    {
        return new Writer(fn(): array
            => [$value, $log ?? Str::mempty()]);
    }

    public function bind(callable $f): Writer
    {
        return new Writer(
            function () use ($f): array {

                list($xs, $log1) = $this->run();

                if (is_null($xs)) {
                    return [null, $log1];
                }

                list ($ys, $log2) = $f($xs)->run();

                return [$ys, $log1->concat($log2)];
            }
        );
    }

    public function map(callable $f): Writer
    {
        return new Writer(
            function () use ($f): array {
                list ($xs, $log) = $this->run();
                return [$f($xs), $log];
            }
        );
    }

    public function ap(Apply $that): Writer
    {
        return $this->bind(
            function (callable $f) use ($that): Writer {
                return $that->map($f);
            }
        );
    }

    public function run()
    {
        return call_user_func($this->action);
    }
}