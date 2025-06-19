<?php 

// it('can create a file instance if the file exists', function () {
//     expect(File::of(__DIR__ .'/demo-dir/file1.txt'))->toBeInstanceOf(File::class);
//     expect(File::of('?'))->toBeInstanceOf(Left::class);
// });

// it('returns Left if the file does not exist', function () {
//     // Assuming 'nonexistent_file.txt' does not exist for the test
//     $file = File::of('nonexistent_file.txt');
//     expect($file)->toBeInstanceOf(Left::class);
//     expect($file->extract()->get())->toBe('File does not exist: nonexistent_file.txt');
// });

// it('can get the size of the file', function () {
//     // Create a temporary file for the test
//     $path = __DIR__ . '/temp.txt';
//     file_put_contents($path, 'Hello, World!');
    
//     // Test the size
//     $file = File::of($path);
//     $size = $file->getSize()->run();
    
//     // Verify the size matches the content
//     expect($size)->toBe(strlen('Hello, World!'));
    
//     // Clean up the test file
//     unlink($path);
// });

// it('can get the extension of the file', function () {
//     // Create a temporary file with an extension
//     $path = __DIR__ . '/test_file.php';
//     file_put_contents($path, '<?php echo "Test";');
    
//     // Test the extension
//     $file = File::of($path);
//     $extension = $file->getExtension()->run();
    
//     // Verify the extension is 'php'
//     expect($extension)->toBe('php');
    
//     // Clean up the test file
//     unlink($path);
// });

// it('can read the contents of the file', function () {
//     // Create a temporary file with some content
//     $path = __DIR__ . '/read_file.txt';
//     file_put_contents($path, 'This is a test.');
    
//     // Test reading the content
//     $file = File::of($path);
//     $content = $file->read()->run();
    
//     // Verify the content is correct
//     expect($content)->toBe('This is a test.');
    
//     // Clean up the test file
//     unlink($path);
// });
