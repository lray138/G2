<?php 

use lray138\G2\{
    Either\Left,
    Lst
};

describe('Pointed', function () {
    
    it('constructs properly', function () {
        
        expect(Lst::of(['a']))->toBeInstanceOf(Lst::class);
        expect(Lst::of(['a' => 0]))->toBeInstanceOf(Left::class);
        expect(Lst::of([]))->toBeInstanceOf(Lst::class);

    });

});

describe('Functor', function() {

    it('map applies function to each element', function () {
        $list = Lst::of([1, 2, 3]);
        $mapped = $list->map(fn($x) => $x * 2);

        expect($mapped)->toBeInstanceOf(Lst::class)
                    ->and($mapped->extract())->toEqual([2, 4, 6])
                    ->and($list->extract())->toEqual([1, 2, 3]); // original unchanged
    });

});

