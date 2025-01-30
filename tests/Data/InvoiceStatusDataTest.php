<?php

use Mrfansi\Xendit\Data\InvoiceStatusData;
use Mrfansi\Xendit\Enums\InvoiceStatus;

test('invoice status data can be created with values', function () {
    $data = new InvoiceStatusData(
        status: 'PENDING'
    );

    expect($data)
        ->status->toBe('PENDING');
});

test('invoice status data can be created with null values', function () {
    $data = new InvoiceStatusData;

    expect($data)
        ->status->toBeNull();
});

test('invoice status data can be created from enum', function () {
    $data = InvoiceStatusData::fromEnum(InvoiceStatus::PENDING);

    expect($data)
        ->status->toBe('PENDING');
});

test('invoice status data can be created from array', function () {
    $data = InvoiceStatusData::fromArray([
        'status' => 'PENDING',
    ]);

    expect($data)
        ->status->toBe('PENDING');
});

test('invoice status data can be converted to array', function () {
    $data = new InvoiceStatusData(
        status: 'PENDING'
    );

    $array = $data->toArray();

    expect($array)
        ->toHaveKeys(['status'])
        ->and($array['status'])->toBe('PENDING');
});
