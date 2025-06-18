<?php 

use lray138\G2\{
    Either\Left,
    Kvm
};


it('constructs properly', function () use ($demo_dir_path) {
        
    expect(Kvm::of(['a' => 0]))->toBeInstanceOf(Kvm::class);
    expect(Kvm::of(['a']))->toBeInstanceOf(Left::class);
    expect(Kvm::of([]))->toBeInstanceOf(Kvm::class);

        // expect(Dir::of("/Users/lray/Sites")
        //     ->exists()
        //     ->extract()
        // )->toBe(true);

});

