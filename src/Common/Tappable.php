<?php

declare(strict_types=1);

namespace lray138\G2\Common;

use function lray138\G2\{
    wrap,
    unwrap
};
use lray138\G2\Either;

trait Tappable
{
    public function tap(callable $fn): self
    {
        $fn($this);
        return $this;
    }

}