<?php namespace lray138\G2;

/**
 * Creates a curried version of a function that waits for N arguments before executing
 * 
 * @param int $arity The number of arguments the function expects
 * @param callable|null $fn The function to curry (optional)
 * @return callable A curried version of the function or a function that takes the callable
 */
function curryN(int $arity, ?callable $fn = null): callable
{
    if ($fn === null) {
        return function (callable $function) use ($arity): callable {
            return curryN($arity, $function);
        };
    }
    
    return function (...$args) use ($fn, $arity) {
        if (count($args) >= $arity) {
            return $fn(...$args);
        }
        
        return function (...$moreArgs) use ($fn, $arity, $args) {
            $allArgs = array_merge($args, $moreArgs);
            if (count($allArgs) >= $arity) {
                return $fn(...$allArgs);
            }
            
            return curryN($arity - count($allArgs), function (...$finalArgs) use ($fn, $allArgs) {
                return $fn(...array_merge($allArgs, $finalArgs));
            });
        };
    };
}

