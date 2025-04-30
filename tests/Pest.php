<?php 

function describeMonad($monadClass) {
    describe("{$monadClass} monad", function () use ($monadClass) {

        $value = 5;
        $f = fn($x) => $x + 1;
        $g = fn($x) => $x * 2;
        $unit = fn($x) => $monadClass::of($x);

        it('satisfies the Left Identity law', function () use ($value, $f, $unit) {
            $result = $unit($value)->bind(fn($x) => $unit($f($x)));
            expect($result->extract())->toBe($f($value));
        });

        it('satisfies the Right Identity law', function () use ($value, $unit) {
            $m = $unit($value);
            $result = $m->bind($unit);
            expect($result->extract())->toBe($m->extract());
        });

        it('satisfies the Associativity law', function () use ($value, $unit, $f, $g) {
            $m = $unit($value);
            $fM = fn($x) => $unit($f($x));
            $gM = fn($x) => $unit($g($x));

            $left = $m->bind($fM)->bind($gM);
            $right = $m->bind(fn($x) => $fM($x)->bind($gM));

            expect($left->extract())->toBe($right->extract());
        });

        it('applies a wrapped function to a wrapped value', function () use ($monadClass) {
            $wrappedFunction = $monadClass::of(fn($x) => $x + 10);
            $wrappedValue = $monadClass::of(5);

            $result = $wrappedFunction->ap($wrappedValue);

            expect($result->extract())->toBe(15);
        });
        
    });
}
