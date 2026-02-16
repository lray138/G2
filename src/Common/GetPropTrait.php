<?php

declare(strict_types=1);

namespace lray138\G2\Common;

use lray138\G2\Nil;

use function lray138\G2\{
    wrap,
    unwrap
};

use lray138\G2\{Either, Maybe};

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
        $stored = unwrap($this->extract());
        $key = unwrap($key);

        if (!isset($stored[$key])) {
            return Nil::unit();
        }

        return wrap($stored[$key]);
    }

    public function maybeProp($key): Maybe
    {
        $stored = unwrap($this->extract());
        $key = unwrap($key);

        if (!isset($stored[$key])) {
            return Maybe::nothing();
        }

        return Maybe::just(wrap($stored[$key]));
    }

    public function mProp($key): Maybe
    {
       return $this->maybeProp($key);
    }

    public function eitherProp($key): Either
    {
        $stored = unwrap($this->extract());
        $key = unwrap($key);

        if (!isset($stored[$key])) {
            return Either::left("prop '$key' not found");
        }

        return Either::right(wrap($stored[$key]));
    }

    public function eProp($key): Either
    {
        return $this->eitherProp($key);
    }

    public function pluck($key) {
        return $this->prop($key);
    }
    
}