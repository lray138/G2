<?php

declare(strict_types=1);

namespace lray138\G2\Common;

use function lray138\G2\{
    wrap,
    unwrap
};
use lray138\G2\Either;

trait GetPropTrait
{
    public function prop($key)
    {
        $stored = unwrap($this->extract());
        $key = unwrap($key);

        if(!isset($stored[$key])) {
            return Either::left("prop '$key' not found");
        }

        return $stored[$key] instanceof Either 
            ? $stored[$key]
            : Either::right(wrap($stored[$key]));
    }
}