<?php 

require dirname(__DIR__) . '/vendor/autoload.php';

use lray138\G2\Writer;

$of = fn($x, $log) => Writer::of($x, $log);

$a = $of(3, "Start")
    ->bind(fn($x) => $of($x + 2, "Add 2"))
    ->bind(fn($x) => $of($x * 2, "Multiply by 2"));

[$value, $log] = $a->run();


var_dump($log);
