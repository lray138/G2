<?php 

require "File/existing-tests.php";

use lray138\G2\{
    Either,
    File
};

// read
it('can read a file', function () {
    
    $f = File::either(dirname(__DIR__) . '/demo-dir/file1.txt');

    // expect($f)->toBeInstanceOf(Either::class);

    // $f->map(function (File $file) {
    //     return $file->getContents();
    // });

    // expect($f->getOrElse(''))->toBe('Hello, World!');

});
