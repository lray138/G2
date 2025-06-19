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

// it('has "magic" toString implemented', function() {
    
// });

// it('concat works', function () {
//     $s = Num::of(1)
//         ->concat(Num::of(3));

//     $s = Num::of(1, "mul")
//         ->concat(Num::of(3, "mul"));

//     expect($s->extract())->toBe(3);
// });