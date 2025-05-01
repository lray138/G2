<?php 

use lray138\G2\{
    Either\Left,
    Arr
};

it('constructs correctly', function () {
    expect(Arr::of('string')->extract())->toBe(['string']);
    expect(Arr::of(['string'])->extract())->toBe(['string']);
    expect(Arr::of([])->extract())->toBe([]);
    expect(Arr::of(null))->toBeInstanceOf(Left::class);
});

it('returns prop', function() {
    $a = Arr::of([
        'prop' => 'value'
    ]);

    expect($a->prop('prop')->extract())->toBe('value');
});