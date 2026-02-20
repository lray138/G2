<?php 

require "Str/existing.php";

use lray138\G2\Str;

it('append correctly', function() {
    expect(
        Str::of('b')->append('c')->get()
    )->toBe('bc');
});

it('prepend correctly', function() {
    expect(
        Str::of('b')->prepend('a')->get()
    )->toBe('ab');
});

require "Str/trim.php";
require "Str/concat.php";