<?php

use Mrfansi\XenditSdk\Data\Fee;

test('fee can be created with valid values', function () {
    $fee = new Fee(
        type: 'admin',
        value: 5000
    );

    expect($fee)
        ->type->toBe('admin')
        ->value->toBe(5000.0);
});

test('fee supports negative values for discounts', function () {
    $fee = new Fee(
        type: 'discount',
        value: -1000
    );

    expect($fee)
        ->type->toBe('discount')
        ->value->toBe(-1000.0);
});

test('fee throws exception for empty type', function () {
    expect(fn () => new Fee(
        type: '',
        value: 5000
    ))->toThrow(InvalidArgumentException::class, 'Fee type cannot be empty');

    expect(fn () => new Fee(
        type: '   ',
        value: 5000
    ))->toThrow(InvalidArgumentException::class, 'Fee type cannot be empty');
});

test('fee can be converted to array', function () {
    $fee = new Fee(
        type: 'shipping',
        value: 15000
    );

    $array = $fee->all();

    expect($array)
        ->toHaveKeys(['type', 'value'])
        ->and($array['type'])->toBe('shipping')
        ->and($array['value'])->toBe(15000.0);
});

test('fee works with different types', function () {
    $types = ['admin', 'shipping', 'tax', 'discount', 'handling'];

    foreach ($types as $type) {
        $fee = new Fee(type: $type, value: 1000);
        expect($fee->type)->toBe($type);
    }
});
