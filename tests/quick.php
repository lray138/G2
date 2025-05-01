<?php 

require dirname(__DIR__) . '/vendor/autoload.php';

use lray138\G2\Writer;

$unit = fn($x, $log) => Writer::unit($x, $log);

$a = $unit(3, "Start")
    ->bind(fn($x) => $unit($x + 2, "Add 2"))
    ->bind(fn($x) => $unit($x * 2, "Multiply by 2"));

[$value, $log] = $a->run();


var_dump($log);
