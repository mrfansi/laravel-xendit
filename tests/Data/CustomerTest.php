<?php

use Mrfansi\Xendit\Data\Address;
use Mrfansi\Xendit\Data\Customer;

/**
 * Test cases for Customer Data Transfer Object
 */
test('customer can be created with values', function () {
    $addresses = [
        new Address(
            city: 'Jakarta',
            country: 'ID',
            postal_code: '12345',
            street_line1: 'Jalan Sudirman',
            street_line2: 'Lantai 5'
        ),
        new Address(
            city: 'Surabaya',
            country: 'ID',
            postal_code: '67890',
            street_line1: 'Jalan Tunjungan',
            street_line2: null
        ),
    ];

    $customer = new Customer(
        given_names: 'John',
        surname: 'Doe',
        email: 'john.doe@example.com',
        mobile_number: '+628123456789',
        addresses: $addresses
    );

    expect($customer)
        ->given_names->toBe('John')
        ->surname->toBe('Doe')
        ->email->toBe('john.doe@example.com')
        ->mobile_number->toBe('+628123456789')
        ->addresses->toBeArray()
        ->addresses->toHaveCount(2);

    expect($customer->addresses[0])
        ->toBeInstanceOf(Address::class)
        ->city->toBe('Jakarta');

    expect($customer->addresses[1])
        ->toBeInstanceOf(Address::class)
        ->city->toBe('Surabaya');
});

test('customer can be created with null values', function () {
    $customer = new Customer;

    expect($customer)
        ->given_names->toBeNull()
        ->surname->toBeNull()
        ->email->toBeNull()
        ->mobile_number->toBeNull()
        ->addresses->toBeNull();
});

test('customer can be created from array', function () {
    $data = [
        'given_names' => 'John',
        'surname' => 'Doe',
        'email' => 'john.doe@example.com',
        'mobile_number' => '+628123456789',
        'addresses' => [
            [
                'city' => 'Jakarta',
                'country' => 'ID',
                'postal_code' => '12345',
                'street_line1' => 'Jalan Sudirman',
                'street_line2' => 'Lantai 5',
            ],
            [
                'city' => 'Surabaya',
                'country' => 'ID',
                'postal_code' => '67890',
                'street_line1' => 'Jalan Tunjungan',
                'street_line2' => null,
            ],
        ],
    ];

    $customer = Customer::fromArray($data);

    expect($customer)
        ->given_names->toBe('John')
        ->surname->toBe('Doe')
        ->email->toBe('john.doe@example.com')
        ->mobile_number->toBe('+628123456789')
        ->addresses->toBeArray()
        ->addresses->toHaveCount(2);

    expect($customer->addresses[0])
        ->toBeInstanceOf(Address::class)
        ->city->toBe('Jakarta');

    expect($customer->addresses[1])
        ->toBeInstanceOf(Address::class)
        ->city->toBe('Surabaya');
});

test('customer can be converted to array', function () {
    $addresses = [
        new Address(
            city: 'Jakarta',
            country: 'ID',
            postal_code: '12345',
            street_line1: 'Jalan Sudirman',
            street_line2: 'Lantai 5'
        ),
        new Address(
            city: 'Surabaya',
            country: 'ID',
            postal_code: '67890',
            street_line1: 'Jalan Tunjungan',
            street_line2: null
        ),
    ];

    $customer = new Customer(
        given_names: 'John',
        surname: 'Doe',
        email: 'john.doe@example.com',
        mobile_number: '+628123456789',
        addresses: $addresses
    );

    $array = $customer->toArray();

    expect($array)
        ->toHaveKeys(['given_names', 'surname', 'email', 'mobile_number', 'addresses'])
        ->and($array['given_names'])->toBe('John')
        ->and($array['surname'])->toBe('Doe')
        ->and($array['email'])->toBe('john.doe@example.com')
        ->and($array['mobile_number'])->toBe('+628123456789')
        ->and($array['addresses'])->toBeArray()
        ->and($array['addresses'])->toHaveCount(2)
        ->and($array['addresses'][0]['city'])->toBe('Jakarta')
        ->and($array['addresses'][1]['city'])->toBe('Surabaya');
});
