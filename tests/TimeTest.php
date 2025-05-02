<?php 

use lray138\G2\{
    Time,
};


describe('constructs properly', function () {

    it('constructs using pointed factory method', function () {
        
        expect(Time::of('now'))->toBeInstanceOf(Time::class);

    });

});