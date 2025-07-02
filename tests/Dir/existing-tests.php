<?php 

use lray138\G2\{
    Dir,
    File,
    Either,
    Lst,
    Either\Left,
    Either\Right,
    Maybe\Just,
    Maybe\Nothing
};

describe("It constructs correctly", function() {

    it('constructs correctly using pointed "of" method', function() {
        // this exists
        expect(Dir::of('/Users/lray/Sites/'))->toBeInstanceOf(Dir::class);
        // this doesn't exist and throws exception
        expect(fn() => Dir::of('/Users/noray/Sites/'))->toThrow(\InvalidArgumentException::class);
    });

    it('constructs correctly using pointed "either" method', function() {
        // this exists
        expect(Dir::either('/Users/lray/Sites/'))->toBeInstanceOf(Right::class);
        // this doesn't exist and returns 
        expect(Dir::either('/Users/noray/Sites/'))->toBeInstanceOf(Left::class);
    });

    it('constructs correctly using pointed "maybe" method', function() {
        // this exists
        expect(Dir::maybe('/Users/lray/Sites/'))->toBeInstanceOf(Just::class);
        // this doesn't exist and returns 
        expect(Dir::maybe('/Users/noray/Sites/'))->toBeInstanceOf(Nothing::class);
    });

});

it('loads children from a directory', function() {
    $dir = Dir::maybe('/Users/lray/Sites/demo-dir')
        ->bind(fn(Dir $d): Either => $d->getChildren())
        ->map(fn(Lst $lst) 
            => $lst->map(fn(File $f) => $f->getBasename()->get())->get()
        )
        ->fold(
            fn($x) => $x,
            fn($x) => $x
        );

    expect($dir)->toEqual(['file1.txt', 'file2.txt']);
});



// it('caches children and avoids redundant scanning', function() {
//     // Simulate that scandir is called only once
//     $this->mock(Dir::class)->shouldReceive('scandir')->once()->andReturn(['file1.txt', 'file2.txt']);
    
//     // Call getChildren once
//     $firstCall = $this->dir->getChildren()->run();
//     expect($firstCall->getValue())->toEqual(['file1.txt', 'file2.txt']);
    
//     // Call getChildren again, should not trigger scandir again
//     $secondCall = $this->dir->getChildren()->run();
//     expect($secondCall->getValue())->toEqual(['file1.txt', 'file2.txt']);
// });

// it('correctly filters files and directories', function() {
//     // Simulate a directory with both files and subdirectories
//     $this->mock(Dir::class)->shouldReceive('scandir')->andReturn(['file1.txt', 'file2.txt', 'subdir1', 'subdir2']);
    
//     // Test getFiles
//     $files = $this->dir->getFiles()->run();
//     expect($files)->toEqual(['file1.txt', 'file2.txt']);
    
//     // Test getDirs
//     $dirs = $this->dir->getDirs()->run();
//     expect($dirs)->toEqual(['subdir1', 'subdir2']);
// });
