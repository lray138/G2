<?php 

// for quick tests

require dirname(__DIR__) . '/vendor/autoload.php';

use lray138\G2\{
    Dir,
    Time,
    Regex,
    File,
    Either,
    Err,
    Str, 
    Boo,
    Lst
};

use function lray138\G2\dump;


date_default_timezone_set('America/New_York');