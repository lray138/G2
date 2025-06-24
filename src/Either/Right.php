<?php

namespace lray138\G2\Either;

use lray138\G2\Common\{
    GonadTrait,
    GetPropTrait 
};

use lray138\G2\Either;
use function lray138\G2\dump;

final class Right extends Either
{
    use GonadTrait;

    // I forgot I did that ;)

    use GetPropTrait;

    public function fold(callable $_, callable $f)
    {
        return $f($this->extract());
    }

    public function getOrLeft() {
        return $this->extract();
    }

public function call($method, ...$args) {
        $value = $this->extract();

        if (is_object($value) && method_exists($value, $method)) {
            try {
                return Right::of($value->$method(...$args));
            } catch (\Throwable $e) {
                return Left::of($e->getMessage());
            }
        }

        return Left::of("Method `$method` does not exist on " . (is_object($value) ? get_class($value) : gettype($value)));
    }

    public function dump() {
        dump($this);
        return $this;
    }
}
