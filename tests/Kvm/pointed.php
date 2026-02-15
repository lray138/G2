<?php 

use lray138\G2\Kvm;
use lray138\G2\Either\Left;

it('constructs properly', function () {
        
    expect(Kvm::of(['a' => 0]))->toBeInstanceOf(Kvm::class);
    expect(Kvm::of([]))->toBeInstanceOf(Kvm::class);

    expect(Kvm::either(['a']))->toBeInstanceOf(Left::class);

});