<?php

declare(strict_types=1);

namespace lray138\G2\Common;

trait ExtendTrait
{

    public function extend(callable $fn) {
        return static::of($fn($this));
    }

    public function extendTo(callable $fn) {
        return $fn($this);
    }
}