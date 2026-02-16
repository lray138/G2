<?php 

use lray138\G2\{
    Either\Left,
    Lst,
    Num
};

// Functional
require "Lst/functor.php";
require "Lst/pointed.php";
require "Lst/monad.php";

// Class
require "Lst/head.php";
require "Lst/mhead.php";
require "Lst/tail.php";
require "Lst/mtail.php";
// require "Lst/filter.php";
// require "Lst/reduce.php";
// require "Lst/unique.php";

// it('returns "prop" returns Either::right vs Either::left', function() {
//     $lst = Lst::of([1, 2, 3]);
//     expect($lst->nth(1)->get()->get())->toBeInstanceOf(Num::class);
//     // I think the reality is the double gets are a smell hear, but likely never
//     // going to happen in "the wild"
//     expect($lst->nth(1)->get()->get())->toBe(2);
// });