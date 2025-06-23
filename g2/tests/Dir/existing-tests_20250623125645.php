<?php 

use lray138\G2\{
    Dir,
    File,
    Either\Left,
    Either\Right
};

it('constructs correctly', function() {
    expect(Dir::of('/Users/lray/Sites/'))->toBeInstanceOf(Right::class);
    expect(Dir::of('/Users/noray/Sites/'))->toBeInstanceOf(Left::class);
});

it('loads children lazily when accessed for the first time', function() {
    $dir = Dir::of('/Users/lray/Sites/demo-dir')
        ->getOrLeft()
        ->getChildren()
        ->map(fn(File $f) => $f->getBasename()->get())
        ->extract();

    expect($dir)->toEqual(['file1.txt', 'file2.txt']);
});

it('gets files correctly', function() {
    $dir = Dir::of('/Users/lray/Sites/demo-dir')
        ->getOrLeft()
        ->getFiles()
        ->map(fn(File $f) => $f->getBasename()->extract())
        ->extract();

    expect($dir)->toEqual(['file1.txt', 'file2.txt']);
});

// it('returns an error if the directory does not exist', function() {
//     // Simulate a non-existing directory
//     $dirResult = Dir::of('invalid/path');
//     expect($dirResult)->toBeInstanceOf(Left::class);
//     expect($dirResult->getValue())->toEqual('Directory does not exist: invalid/path');
// });

// it('handles empty directory content correctly', function() {
//     // Simulate an empty directory
//     $this->mock(Dir::class)->shouldReceive('scandir')->andReturn([]);
    
//     $children = $this->dir->getChildren()->run();
//     expect($children)->toBeInstanceOf(Right::class);
//     expect($children->getValue())->toEqual([]);  // Should be an empty array for an empty directory
// });

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
