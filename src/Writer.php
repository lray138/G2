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
        return new Writer(
            function () use ($value): array {
                return [$value, $log ?? Str::mempty()];
            }
        );
    }

    public function bind(callable $f): Writer
    {
        return new Writer(
            function () use ($f): array {

                list($xs, $log1) = $this->run();

                // adding this to end execution of script without needing
                // the overhead of the example from ChatGPT

                if (is_null($xs)) {
                    return [null, $log1];
                }

                list ($ys, $log2) = $f($xs)->run();

                if (is_null($ys)) {
                    if (is_string($log2) && is_string($log1)) {
                        $log2 = $log2 . $log1;
                    } else {
                        $log2 = $log2->concat(ArrType::of(["Computation ended."]));
                    }
                }

                $log = Str::mempty();

                return [$ys, $log];
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

    public function ap(Apply $a): Apply
    {
        return $a;
    }

    public function run()
    {
        return call_user_func($this->action);
    }
}
