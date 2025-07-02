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
        try {
            $value = $this->expect($key);
            return \lray138\G2\Either::right(wrap($value));
        } catch (\lray138\G2\Err $err) {
            return \lray138\G2\Either::left($err);
        } catch (\Exception $e) {
            return \lray138\G2\Either::left(\lray138\G2\Err::of($e->getMessage()));
        }
    }

    public function pluck($key) {
        return $this->prop($key);
    }
    
}