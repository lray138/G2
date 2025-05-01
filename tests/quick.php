<?php 

require dirname(__DIR__) . '/vendor/autoload.php';

use lray138\G2\{
    Str, 
    Reader
};


$x = 10;
$f = fn($n) => Reader::of($n * 2);

$a = Reader::of($x)->bind($f);
$b = $f($x);

var_dump($a->run(''));

$env = "ENV"; // any dummy env
