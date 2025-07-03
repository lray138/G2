
<?php 

use lray138\G2\Str;

it('constructs properly', function () {
    $s = Str::of('string');

    expect($s)->toBeInstanceOf(Str::class);
    expect($s->extract())->toBe('string');

    $object = new stdClass();
    expect(fn() => Str::of($object))->toThrow(\InvalidArgumentException::class);

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


it('satisfies the Left Identity law for Str', function () {
    $value = "Hello";
    $f = fn($x) => Str::of(strtoupper($x));

    $bound = Str::of($value)->bind($f);
    $direct = $f($value);

    expect($bound->extract())->toBe($direct->extract());
});

it('satisfies the Right Identity law for Str', function () {
    $m = Str::of("World");
    $bound = $m->bind(fn($x) => Str::of($x));

    expect($bound->extract())->toBe($m->extract());
});

it('satisfies the Associativity law for Str', function () {
    $m = Str::of("chain");
    $f = fn($x) => Str::of(strtoupper($x));
    $g = fn($x) => Str::of($x . "!");

    $left = $m->bind($f)->bind($g);
    $right = $m->bind(fn($x) => $f($x)->bind($g));

    expect($left->extract())->toBe($right->extract());
});

it('satisfies the Map Law for Str', function () {
    $value = "hi";
    $f = fn($x) => ucfirst($x);

    $mapped = Str::of($value)->map($f);
    $expected = Str::of($f($value));

    expect($mapped->extract())->toBe($expected->extract());
});

it('ensures map is consistent with bind for Str', function () {
    $f = fn($x) => strtoupper($x);
    $m = Str::of("test");

    $mapResult = $m->map($f);
    $bindResult = $m->bind(fn($x) => Str::of($f($x)));

    expect($mapResult->extract())->toBe($bindResult->extract());
});
