<?php 

use lray138\G2\{
    Either\Left,
    Either\Right,
    Kvm,
    Num,
    Str,
    Boo,
    Nil,
    Maybe\Just,
    Maybe\Nothing,
    Result\Ok,
    Result\Err
};

use function lray138\G2\identity;

it('returns "prop" returns appropriate type', function() {

    $kvm = Kvm::of([
        'a' => 1,
        'b' => '2',
        'c' => false
    ]);

    expect($kvm->prop('a'))->toBeInstanceOf(Num::class);
    expect($kvm->prop('b'))->toBeInstanceOf(Str::class);
    expect($kvm->prop('c'))->toBeInstanceOf(Boo::class);
    expect($kvm->prop('d'))->toBeInstanceOf(Nil::class);
});

it('returns "mprop" returns appropriate type', function() {

    $kvm = Kvm::of([
        'a' => 1,
        'b' => '2',
        'c' => false
    ]);

    expect($kvm->mprop('a'))->toBeInstanceOf(Just::class);
    expect($kvm->mprop('a')->get())->toBeInstanceOf(Num::class);
    expect($kvm->mprop('b'))->toBeInstanceOf(Just::class);
    expect($kvm->mprop('b')->get())->toBeInstanceOf(Str::class);
    expect($kvm->mprop('c'))->toBeInstanceOf(Just::class);
    expect($kvm->mprop('c')->get())->toBeInstanceOf(Boo::class);
    expect($kvm->mprop('asdf'))->toBeInstanceOf(Nothing::class);
    expect($kvm->mprop('z'))->toBeInstanceOf(Nothing::class);

});

it('returns "prop" path values and Nil for missing path', function() {
    $kvm = Kvm::of([
        'col_1' => [
            'content' => 'hello'
        ]
    ]);

    expect($kvm->prop('col_1.content'))->toBeInstanceOf(Str::class);
    expect($kvm->prop('col_1.missing'))->toBeInstanceOf(Nil::class);
});

it('returns "tryProp" as Result', function() {
    $kvm = Kvm::of([
        'col_1' => [
            'content' => 'hello'
        ]
    ]);

    expect($kvm->tryProp('col_1.content'))->toBeInstanceOf(Ok::class);
    expect($kvm->tryProp('col_1.missing'))->toBeInstanceOf(Err::class);
});

it('returns nested list/object path values with bracket indexes', function() {
    $kvm = Kvm::of([
        'cols' => [
            [
                'attrs' => [
                    'title' => 'first title'
                ]
            ],
            [
                'attrs' => [
                    'title' => 'second title'
                ]
            ]
        ]
    ]);

    expect($kvm->prop('cols[0].attrs.title'))->toBeInstanceOf(Str::class);
    expect($kvm->prop('cols[0].attrs.title')->get())->toBe('first title');
    expect($kvm->prop('cols[0].attrs.tiatle'))->toBeInstanceOf(Nil::class);

    expect($kvm->prop('cols[1].attrs.title'))->toBeInstanceOf(Str::class);
    expect($kvm->prop('cols[1].attrs.title')->get())->toBe('second title');
});

