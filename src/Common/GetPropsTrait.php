<?php

declare(strict_types=1);

namespace lray138\G2\Common;

use function lray138\G2\{
    wrap,
    unwrap
};
use lray138\G2\Kvm;

trait GetPropsTrait
{
    /**
     * Get multiple properties by keys.
     *
     * @param array|string[] $keys
     * @return array Associative array of key => value
     */
    public function props($keys): self
    {
        $keys = unwrap($keys);
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = array_key_exists($key, $this->value)
                ? $this->value[$key]
                : null;
        }

        return self::of($result);
    }

}