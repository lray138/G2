<?php

declare(strict_types=1);

namespace lray138\G2\Common;

use function lray138\G2\{
    wrap,
    unwrap
};

use lray138\G2\Result;

trait GetPropTrait
{
    public function expect($key)
    {
        $stored = unwrap($this->extract());
        $key = unwrap($key);

        if (!isset($stored[$key])) {
            throw new \Exception("prop '$key' not found");
        }

        return wrap($stored[$key]);
    }

    public function key($key) {
        return $this->prop($key);
    }

    public function prop($key)
    {
        return Result::try(function() use ($key) {
            return $this->expect($key);
        });
    }

    public function pluck($key) {
        return $this->prop($key);
    }
    
}