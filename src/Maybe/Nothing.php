<?php

namespace lray138\G2\Maybe;

use lray138\G2\Maybe;

class Nothing extends Maybe
{
    protected $value;

    public const of  = __CLASS__ . '::of';

    private function __construct($value)
    {
        $this->value = $value;
    }

    public static function of($value)
    {
        return new static($value);
    }

    public function extract()
    {
        return $this->value;
    }
}