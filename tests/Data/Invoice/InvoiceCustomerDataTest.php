<?php

use Mrfansi\LaravelXendit\Data\Customer\AddressData;
use Mrfansi\LaravelXendit\Data\Invoice\InvoiceCustomerData;
use Mrfansi\LaravelXendit\Enums\AddressCategory;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;

test('can create with required fields', function () {
    $customer = new InvoiceCustomerData(
        givenNames: 'John'
    );

    expect($customer)
        ->toBeInstanceOf(InvoiceCustomerData::class)
        ->givenNames->toBe('John');
});

test('can create with all fields', function () {
    $address = new AddressData(
        country: 'ID',
        provinceState: 'Jakarta',
        city: 'South Jakarta',
        streetLine1: 'Jl. Sudirman',
        category: AddressCategory::HOME,
        isPrimary: true
    );

    $customer = new InvoiceCustomerData(
        givenNames: 'John',
        surname: 'Doe',
        email: 'john.doe@example.com',
        mobileNumber: '+6281234567890',
        nationality: 'ID',
        placeOfBirth: 'Jakarta',
        dateOfBirth: '1990-01-01',
        gender: 'MALE',
        addresses: [$address]
    );

    expect($customer)
        ->toBeInstanceOf(InvoiceCustomerData::class)
        ->givenNames->toBe('John')
        ->surname->toBe('Doe')
        ->email->toBe('john.doe@example.com')
        ->mobileNumber->toBe('+6281234567890')
        ->nationality->toBe('ID')
        ->placeOfBirth->toBe('Jakarta')
        ->dateOfBirth->toBe('1990-01-01')
        ->gender->toBe('MALE')
        ->addresses->toHaveCount(1)
        ->addresses->each->toBeInstanceOf(AddressData::class);
});

test('validates email format', function () {
    expect(fn () => new InvoiceCustomerData(
        givenNames: 'John',
        email: 'invalid-email'
    ))->toThrow(ValidationException::class, 'Invalid email format');
});

test('validates mobile number format', function () {
    expect(fn () => new InvoiceCustomerData(
        givenNames: 'John',
        mobileNumber: '081234567890'
    ))->toThrow(
        ValidationException::class,
        'Mobile number must be in E164 format (e.g., +6281234567890)'
    );
});

test('validates addresses type', function () {
    expect(fn () => new InvoiceCustomerData(
        givenNames: 'John',
        addresses: [['country' => 'ID']] // Invalid address format
    ))->toThrow(ValidationException::class, 'Each address must be an instance of AddressData');
});

test('toArray excludes null values', function () {
    $address = new AddressData(
        country: 'ID',
        city: 'Jakarta'
    );

    $customer = new InvoiceCustomerData(
        givenNames: 'John',
        email: 'john@example.com',
        addresses: [$address]
    );

    $array = $customer->toArray();

    expect($array)
        ->toHaveKeys(['given_names', 'email', 'addresses'])
        ->not->toHaveKeys([
            'surname',
            'mobile_number',
            'nationality',
            'place_of_birth',
            'date_of_birth',
            'gender',
        ]);

    // Verify address array structure
    expect($array['addresses'])
        ->toHaveCount(1)
        ->and($array['addresses'][0])
        ->toHaveKeys(['country', 'city'])
        ->not->toHaveKey('province_state');
});
