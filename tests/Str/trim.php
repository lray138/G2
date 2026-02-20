
<?php 

use lray138\G2\Str;

it("trims correctly", function () {
    $original = Str::of("   hello world   ");
    $trimmed  = $original->trim();

    // returns correct type
    expect($trimmed)->toBeInstanceOf(Str::class);

    // trims whitespace
    expect($trimmed->get())->toBe("hello world");

    // does not mutate original
    expect($original->get())->toBe("   hello world   ");
});

it("returns empty string when trimming only whitespace", function () {

    $str = Str::of("     ");
    $trimmed = $str->trim();

    expect($trimmed->get())->toBe("");
});

it("does nothing if no whitespace exists", function () {

    $str = Str::of("hello");
    $trimmed = $str->trim();

    expect($trimmed->get())->toBe("hello");
});

it("trims specific characters", function () {

    $str = Str::of("--hello--");
    $trimmed = $str->trim("-");

    expect($trimmed->get())->toBe("hello");
});