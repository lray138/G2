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
    Str
};

use function lray138\G2\dump;

date_default_timezone_set('America/New_York');

$f = File::either(dirname(__DIR__) . '/tests/demo-dir/file1.txt')
    ->bind(fn(File $f) 
        => $f->getContents()
            ->map(fn(Str $s) => Str::of("whatever"))
            ->bind(fn(Str $s) => $f->putContents($s))
    )
    ->fold(
        fn(Err $e) => $e,
        fn(File $f) => $f
    );

dump($f);
