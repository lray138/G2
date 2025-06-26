<?php 

// for quick tests

require dirname(__DIR__) . '/vendor/autoload.php';

use lray138\G2\{
    Dir,
    Time,
    Regex
};

use function lray138\G2\dump;

date_default_timezone_set('America/New_York');


// ok yeah and I'm glad we put that there, because if we need to 
// delay action we should wrap it in a different Context

// 

$regex = Regex::of('/[a-z');

dump($regex);