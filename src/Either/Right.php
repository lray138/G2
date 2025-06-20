<?php

namespace lray138\G2\Either;

use lray138\G2\Common\{
    GonadTrait,
    GetPropTrait 
};

use lray138\G2\Either;

final class Right extends Either
{
    use GonadTrait;

    // I forgot I did that ;)

    use GetPropTrait;
}
