<?php 

// for quick tests

require dirname(__DIR__) . '/vendor/autoload.php';

use lray138\G2\{
    Dir,
    Time,
    Regex,
    File,
    Either,
    Err
};

use function lray138\G2\dump;

date_default_timezone_set('America/New_York');

$f = File::either(dirname(__DIR__) . '/demo-dfir/file1.txt')
    ->bind(fn(File $f) => $f->getContents())
    ->fold(
        fn(Err $e) => $e,
        fn(Str $c) => $c
    );

dump($f);