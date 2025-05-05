<?php 

use lray138\G2\{
    Time,
    Str,
    Either\Left
};


describe('constructs properly', function () {

    it('constructs using pointed factory method', function () {
        expect(Time::of('2024-10-12'))->toBeInstanceOf(Time::class);
        
        expect(Time::of('2024-10-03')->format('Y-m-d')->get())->toBe('2024-10-03');

    
    });

    

});