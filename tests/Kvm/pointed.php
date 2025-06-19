<?php 

use lray138\G2\Kvm;
use lray138\G2\Either\Left;

it('constructs properly', function () {
        
    expect(Kvm::of(['a' => 0]))->toBeInstanceOf(Kvm::class);
    expect(Kvm::of(['a']))->toBeInstanceOf(Left::class);
    expect(Kvm::of([]))->toBeInstanceOf(Kvm::class);

        // expect(Dir::of("/Users/lray/Sites")
        //     ->exists()
        //     ->extract()
        // )->toBe(true);

});
