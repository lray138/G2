<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\{Apply, Monad};

class Writer implements Monad
{
    private $action;

    private function __construct(callable $action)
    {
        $this->action = $action;
    }

    public static function of($value): Writer 
    {
        return new Writer(
            function () use ($value) : array
            {
                return [$value, ""];
            }
        );
    }

    public function bind(callable $f) : Writer
    {
        return new Writer(
            function () use ($f) : array
            {

                list($xs, $log1) = $this->run();

                // adding this to end execution of script without needing
                // the overhead of the example from ChatGPT

                if(is_null($xs)) {
                    return [null, $log1];
                }

                list ($ys, $log2) = $f($xs)->run();

                // @todo fix this later
                // great node here man, c'mon... 

                // so, I'm back here and Jan 10 at 12:56 and was going to use an array
                // to pass data, but really if we just use "null" as the kill then anything
                // else is the data we want to pass.

                if(is_null($ys)) {

                    if(is_string($log2) && is_string($log1)) {
                        $log2 = $log2 . $log1;
                    } else {
                        $log2 = $log2->concat(ArrType::of(["Computation ended."]));
                    }

                }
                
                if(is_string($log2) && is_string($log1)) {
                    $log = $log2 . $log1;
                } 

                return [$ys, $log];
            }
        );
    }

    public function map(callable $f) : Writer
    {
        return new Writer(
            function () use ($f) : array
            {
                list ($xs, $log) = $this->run();
                return [$f($xs), $log];
            }
        );
    }

    public function ap(Apply $a): Apply {
        return $a;
    }

    public function run()
    {
        return call_user_func($this->action);
    }

}
