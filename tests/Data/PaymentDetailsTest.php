<?php

use Mrfansi\Xendit\Data\PaymentDetails;
use Mrfansi\Xendit\Enums\QrisSource;

test('payment details can be created with values', function () {
    $details = new PaymentDetails(
        receipt_id: '120318237',
        source: QrisSource::OVO
    );

    expect($details)
        ->receipt_id->toBe('120318237')
        ->source->toBe(QrisSource::OVO);
});

test('payment details can be created with null values', function () {
    $details = new PaymentDetails();

    expect($details)
        ->receipt_id->toBeNull()
        ->source->toBeNull();
});

test('payment details can be created from array', function () {
    $details = PaymentDetails::fromArray([
        'receipt_id' => '120318237',
        'source' => 'OVO',
    ]);

    expect($details)
        ->receipt_id->toBe('120318237')
        ->source->toBe(QrisSource::OVO);
});

test('payment details can be converted to array', function () {
    $details = new PaymentDetails(
        receipt_id: '120318237',
        source: QrisSource::OVO
    );

    $array = $details->toArray();

    expect($array)
        ->toHaveKeys(['receipt_id', 'source'])
        ->and($array['receipt_id'])->toBe('120318237')
        ->and($array['source']->value)->toBe('OVO');
});
