<?php 

use function lray138\G2\wrap;

use lray138\G2\{
    Regex
};

use lray138\G2\Either\Left;
use lray138\G2\Either\Right;

it('constructs a Regex with a valid pattern', function () {
    $regex = Regex::of('/\\d+/');
    expect($regex)->toBeInstanceOf(Right::class);
});

it('fails to construct a Regex with an invalid pattern', function () {
    $regex = Regex::of('/[a-z');
    expect($regex)->toBeInstanceOf(Left::class);
});

// it('returns Left if no match is found', function () {
//     $regex = Regex::of('/xyz/')->getOrLeft();
//     $result = $regex->match('abc123def');
//     expect($result)->toBeInstanceOf(Left::class);
// });

// it('matches all occurrences in a string', function () {
//     $regex = Regex::of('/\\d+/')->getOrLeft();
//     $result = $regex->matchAll('abc123def456');
//     expect($result)->toBeInstanceOf(Right::class);
//     expect($result->extract()[0][0])->toBe('123');
//     expect($result->extract()[0][1])->toBe('456');
// });

// it('replaces pattern in a string', function () {
//     $regex = Regex::of('/\\d+/')->getOrLeft();
//     $result = $regex->replace('abc123def456', 'X');
//     expect($result)->toBeInstanceOf(Right::class);
//     expect($result->extract()->extract())->toBe('abcXdefX');
// });

// it('splits a string by pattern', function () {
//     $regex = Regex::of('/,\s*/')->getOrLeft();
//     $result = $regex->split('a, b, c');
//     expect($result)->toBeInstanceOf(Right::class);
//     expect($result->extract()[0])->toBe('a');
//     expect($result->extract()[1])->toBe('b');
//     expect($result->extract()[2])->toBe('c');
// });

// it('tests if a pattern exists in a string', function () {
//     $regex = Regex::of('/foo/')->getOrLeft();
//     $result = $regex->test('foobar');
//     expect($result)->toBeInstanceOf(Right::class);
//     expect($result->extract()->extract())->toBeTrue();
// });

// it('returns false if pattern does not exist in string', function () {
//     $regex = Regex::of('/foo/')->getOrLeft();
//     $result = $regex->test('barbaz');
//     expect($result)->toBeInstanceOf(Right::class);
//     expect($result->extract()->extract())->toBeFalse();
// });

