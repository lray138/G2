<?php 

// for quick tests

require dirname(__DIR__) . '/vendor/autoload.php';

use lray138\G2\{
    Dir,
    Time
};

use function lray138\G2\dump;

date_default_timezone_set('America/New_York');

// $t = Time::of('now')
//     ->format('mysql')
//     ->run();

// ok yeah and I'm glad we put that there, because if we need to 
// delay action we should wrap it in a different Context

// 

$d = Dir::of(__DIR__)
    ->getFiles();

dump($d);