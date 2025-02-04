<?php

use Mrfansi\LaravelXendit\Data\Address;

/**
 * Test cases for Address Data Transfer Object
 */
test('address can be created with values', function () {
    $address = new Address(
        city: 'Jakarta',
        country: 'ID',
        postal_code: '12345',
        street_line1: 'Jalan Sudirman',
        street_line2: 'Lantai 5'
    );

    expect($address)
        ->city->toBe('Jakarta')
        ->country->toBe('ID')
        ->postal_code->toBe('12345')
        ->street_line1->toBe('Jalan Sudirman')
        ->street_line2->toBe('Lantai 5');
});

test('address can be created with null values', function () {
    $address = new Address;

    expect($address)
        ->city->toBeNull()
        ->country->toBeNull()
        ->postal_code->toBeNull()
        ->street_line1->toBeNull()
        ->street_line2->toBeNull();
});

test('address can be created from array', function () {
    $address = Address::fromArray([
        'city' => 'Jakarta',
        'country' => 'ID',
        'postal_code' => '12345',
        'street_line1' => 'Jalan Sudirman',
        'street_line2' => 'Lantai 5',
    ]);

    expect($address)
        ->city->toBe('Jakarta')
        ->country->toBe('ID')
        ->postal_code->toBe('12345')
        ->street_line1->toBe('Jalan Sudirman')
        ->street_line2->toBe('Lantai 5');
});

test('address can be converted to array', function () {
    $address = new Address(
        city: 'Jakarta',
        country: 'ID',
        postal_code: '12345',
        street_line1: 'Jalan Sudirman',
        street_line2: 'Lantai 5'
    );

    $array = $address->toArray();

    expect($array)
        ->toHaveKeys(['city', 'country', 'postal_code', 'street_line1', 'street_line2'])
        ->and($array['city'])->toBe('Jakarta')
        ->and($array['country'])->toBe('ID')
        ->and($array['postal_code'])->toBe('12345')
        ->and($array['street_line1'])->toBe('Jalan Sudirman')
        ->and($array['street_line2'])->toBe('Lantai 5');
});
