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