<?php

namespace lray138\G2\Traits;

use FunctionalPHP\FantasyLand\{Apply, Functor};

trait ValueOfTrait
{
    public function extract()
    {
        return $this->value;
    }

    public function get()
    {
        return $this->extract();
    }
}
