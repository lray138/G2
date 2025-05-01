<?php

declare(strict_types=1);

namespace lray138\G2\Common;

trait PointedTrait
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public static function of($value)
    {
        return new static($value);
    }
}
