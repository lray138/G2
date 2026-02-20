<?php

use lray138\G2\{
    Num,
    Either\Left
};

it('constructs correctly', function () {
    expect(Num::of(1)->extract())->toBe(1);
    expect(Num::of('str'))->toBeInstanceOf(Left::class);
    expect(Num::of('1')->extract())->toBe(1);
    expect(Num::of('1.0')->extract())->toBe(1.0);
});

it('mempty works', function () {
    expect(Num::mempty()->extract())->toBe(0);
    expect(Num::prod()->extract())->toBe(1);
    expect(Num::sum()->extract())->toBe(0);
});

