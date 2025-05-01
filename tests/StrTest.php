<?php 

use lray138\G2\Str;

it('', function () {
    $s = Str::of('string');

    expect($s->extract())->toBe('string');
});

it('mempty works', function () {
    $s = Str::mempty();

    expect($s->extract())->toBe('');

});

it('concat works', function () {
    $s = Str::of('Hello ')
        ->concat(Str::of('World!'));

    expect($s->extract())->toBe('Hello World!');
});