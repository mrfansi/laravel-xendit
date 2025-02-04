<?php

use Mrfansi\LaravelXendit\Data\PaymentMethodData;
use Mrfansi\LaravelXendit\Enums\PaymentMethod;

test('payment method data can be created with values', function () {
    $data = new PaymentMethodData(
        type: 'OVO',
        reusability: true
    );

    expect($data)
        ->type->toBe('OVO')
        ->reusability->toBeTrue();
});

test('payment method data can be created with null values', function () {
    $data = new PaymentMethodData;

    expect($data)
        ->type->toBeNull()
        ->reusability->toBeNull();
});

test('payment method data can be created from enum', function () {
    $data = PaymentMethodData::fromEnum(PaymentMethod::OVO);

    expect($data)
        ->type->toBe('OVO')
        ->reusability->toBeNull();
});

test('payment method data can be created from array', function () {
    $data = PaymentMethodData::fromArray([
        'type' => 'OVO',
        'reusability' => true,
    ]);

    expect($data)
        ->type->toBe('OVO')
        ->reusability->toBeTrue();
});

test('payment method data can be converted to array', function () {
    $data = new PaymentMethodData(
        type: 'OVO',
        reusability: true
    );

    $array = $data->toArray();

    expect($array)
        ->toHaveKeys(['type', 'reusability'])
        ->and($array['type'])->toBe('OVO')
        ->and($array['reusability'])->toBeTrue();
});
