<?php 

use lray138\G2\Lst;

describe('Functor', function() {

    it('map applies function to each element', function () {
        $list = Lst::of([1, 2, 3]);
        $mapped = $list->map(fn($x) => $x * 2);

        expect($mapped)->toBeInstanceOf(Lst::class)
                    ->and($mapped->extract())->toEqual([2, 4, 6])
                    ->and($list->extract())->toEqual([1, 2, 3]); // original unchanged
    });

    it('maps over a list of lists without flattening', function () {
        $lst = Lst::of([[1, 2], [3, 4]]);

        $result = $lst->map(fn($arr) => array_map(fn($x) => $x * 10, $arr));

        expect($result->get())->toEqual([
            [10, 20],
            [30, 40],
        ]);
    });

});