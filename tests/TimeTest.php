<?php 

use lray138\G2\{
    Time,
    Str,
    Either\Left
};


describe('constructs properly', function () {

    it('constructs using pointed factory method', function () {
        expect(Time::of('now'))->toBeInstanceOf(Time::class);
        expect(Time::of('2024-01-02')->extract())->toBeInstanceOf(Left::class);
        expect(Time::of('2024-02-11')->format('Y-m-d')->run())->toBeInstanceOf(Str::class);
        expect(Time::of('2024-02-11')->format('Y-m-d')->run()->extract())->toBe('2024-02-11');
        expect(Time::of('2024-02-11')
            ->getDaysInMonthNum()
            ->bind(fn($x) => $x->extract())
            ->run()
        )->toBe(29);
    });

});