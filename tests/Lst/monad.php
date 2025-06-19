<?php 

use lray138\G2\Lst;

describe('Chain', function () {
    
    it('binds properly to flatten nested Lst', function () {
        $lst = Lst::of([1, 2, 3]);

        $result = $lst->bind(fn($x) => Lst::of([$x, $x * 10]));

        expect($result->get())->toEqual([1, 10, 2, 20, 3, 30]);
    });

});