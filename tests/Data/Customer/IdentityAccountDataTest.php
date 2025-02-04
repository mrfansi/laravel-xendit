<?php

use Mrfansi\LaravelXendit\Data\Customer\IdentityAccountData;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;

test('it can be instantiated with only required parameters', function () {
    $account = new IdentityAccountData(
        type: 'BANK_ACCOUNT'
    );

    expect($account)
        ->toBeInstanceOf(IdentityAccountData::class)
        ->type->toBe('BANK_ACCOUNT')
        ->company->toBeNull()
        ->description->toBeNull()
        ->country->toBeNull()
        ->properties->toBeNull();
});

test('it can be instantiated with all parameters', function () {
    $properties = [
        'account_number' => '1234567890',
        'account_holder_name' => 'John Doe',
        'swift_code' => 'SWIFT123',
        'account_type' => 'Savings',
        'account_details' => 'Details here',
        'currency' => 'USD',
    ];

    $account = new IdentityAccountData(
        type: 'BANK_ACCOUNT',
        company: 'Test Bank',
        description: 'Primary account',
        country: 'ID',
        properties: $properties
    );

    expect($account)
        ->toBeInstanceOf(IdentityAccountData::class)
        ->type->toBe('BANK_ACCOUNT')
        ->company->toBe('Test Bank')
        ->description->toBe('Primary account')
        ->country->toBe('ID')
        ->properties->toBe($properties);
});

test('toArray returns only required fields when optional fields are null', function () {
    $account = new IdentityAccountData(
        type: 'BANK_ACCOUNT'
    );

    expect($account->toArray())
        ->toBe([
            'type' => 'BANK_ACCOUNT',
        ])
        ->not->toHaveKeys([
            'company',
            'description',
            'country',
            'properties',
        ]);
});

test('toArray includes all non-null fields', function () {
    $properties = [
        'account_number' => '1234567890',
        'account_holder_name' => 'John Doe',
    ];

    $account = new IdentityAccountData(
        type: 'BANK_ACCOUNT',
        company: 'Test Bank',
        description: 'Primary account',
        country: 'ID',
        properties: $properties
    );

    expect($account->toArray())->toBe([
        'type' => 'BANK_ACCOUNT',
        'company' => 'Test Bank',
        'description' => 'Primary account',
        'country' => 'ID',
        'properties' => $properties,
    ]);
});

test('validates type', function () {
    // Valid types
    $validTypes = [
        'BANK_ACCOUNT',
        'EWALLET',
        'CREDIT_CARD',
        'PAY_LATER',
        'OTC',
        'QR_CODE',
        'SOCIAL_MEDIA',
    ];

    foreach ($validTypes as $type) {
        expect(fn () => new IdentityAccountData($type))
            ->not->toThrow(ValidationException::class);
    }

    // Invalid type
    expect(fn () => new IdentityAccountData('INVALID_TYPE'))
        ->toThrow(ValidationException::class, 'Type must be one of: '.implode(', ', $validTypes));
});

test('validates bank account properties', function () {
    // Valid properties
    expect(fn () => new IdentityAccountData(
        type: 'BANK_ACCOUNT',
        properties: [
            'account_number' => '1234567890',
            'account_holder_name' => 'John Doe',
            'swift_code' => 'SWIFT123',
            'account_type' => 'Savings',
            'account_details' => 'Details here',
            'currency' => 'USD',
        ]
    ))->not->toThrow(ValidationException::class);

    // Missing required fields
    expect(fn () => new IdentityAccountData(
        type: 'BANK_ACCOUNT',
        properties: []
    ))->toThrow(ValidationException::class, 'Bank account number is required');

    // Invalid account number format
    expect(fn () => new IdentityAccountData(
        type: 'BANK_ACCOUNT',
        properties: [
            'account_number' => 'ABC@123',
            'account_holder_name' => 'John Doe',
        ]
    ))->toThrow(ValidationException::class, 'Bank account number must be alphanumeric');

    // Invalid currency format
    expect(fn () => new IdentityAccountData(
        type: 'BANK_ACCOUNT',
        properties: [
            'account_number' => '1234567890',
            'account_holder_name' => 'John Doe',
            'currency' => 'INVALID',
        ]
    ))->toThrow(ValidationException::class, 'Currency must be a valid ISO 4217 code (3 letters)');
});

test('validates e-wallet properties', function () {
    // Valid properties
    expect(fn () => new IdentityAccountData(
        type: 'EWALLET',
        properties: [
            'account_number' => '1234567890',
            'account_holder_name' => 'John Doe',
            'currency' => 'USD',
        ]
    ))->not->toThrow(ValidationException::class);

    // Missing required fields
    expect(fn () => new IdentityAccountData(
        type: 'EWALLET',
        properties: []
    ))->toThrow(ValidationException::class, 'E-wallet account number is required');
});

test('validates credit card properties', function () {
    // Valid properties
    expect(fn () => new IdentityAccountData(
        type: 'CREDIT_CARD',
        properties: [
            'token_id' => 'token123',
        ]
    ))->not->toThrow(ValidationException::class);

    // Missing required fields
    expect(fn () => new IdentityAccountData(
        type: 'CREDIT_CARD',
        properties: []
    ))->toThrow(ValidationException::class, 'Credit card token ID is required');
});

test('validates OTC properties', function () {
    // Valid properties
    expect(fn () => new IdentityAccountData(
        type: 'OTC',
        properties: [
            'payment_code' => 'CODE123',
            'expires_at' => '2024-12-31',
        ]
    ))->not->toThrow(ValidationException::class);

    // Missing required fields
    expect(fn () => new IdentityAccountData(
        type: 'OTC',
        properties: []
    ))->toThrow(ValidationException::class, 'OTC payment code is required');

    // Invalid date format
    expect(fn () => new IdentityAccountData(
        type: 'OTC',
        properties: [
            'payment_code' => 'CODE123',
            'expires_at' => '2024/12/31',
        ]
    ))->toThrow(ValidationException::class, 'Expiry date must be in YYYY-MM-DD format');
});

test('validates QR code properties', function () {
    // Valid properties
    expect(fn () => new IdentityAccountData(
        type: 'QR_CODE',
        properties: [
            'qr_string' => 'qr123',
        ]
    ))->not->toThrow(ValidationException::class);

    // Missing required fields
    expect(fn () => new IdentityAccountData(
        type: 'QR_CODE',
        properties: []
    ))->toThrow(ValidationException::class, 'QR code string is required');
});

test('validates pay later properties', function () {
    // Valid properties
    expect(fn () => new IdentityAccountData(
        type: 'PAY_LATER',
        properties: [
            'account_id' => 'acc123',
            'account_holder_name' => 'John Doe',
            'currency' => 'USD',
        ]
    ))->not->toThrow(ValidationException::class);

    // Missing required fields
    expect(fn () => new IdentityAccountData(
        type: 'PAY_LATER',
        properties: []
    ))->toThrow(ValidationException::class, 'Pay later account ID is required');
});

test('validates social media properties', function () {
    // Valid properties
    expect(fn () => new IdentityAccountData(
        type: 'SOCIAL_MEDIA',
        properties: [
            'account_id' => 'user123',
            'account_handle' => '@user123',
        ]
    ))->not->toThrow(ValidationException::class);

    // Missing required fields
    expect(fn () => new IdentityAccountData(
        type: 'SOCIAL_MEDIA',
        properties: []
    ))->toThrow(ValidationException::class, 'Social media account ID is required');
});

test('setter methods update properties and return self', function () {
    $account = new IdentityAccountData(
        type: 'BANK_ACCOUNT'
    );

    $properties = [
        'account_number' => '1234567890',
        'account_holder_name' => 'John Doe',
    ];

    $result = $account
        ->setType('EWALLET')
        ->setCompany('Test Company')
        ->setDescription('Test Description')
        ->setCountry('ID')
        ->setProperties($properties);

    expect($result)
        ->toBeInstanceOf(IdentityAccountData::class)
        ->type->toBe('EWALLET')
        ->company->toBe('Test Company')
        ->description->toBe('Test Description')
        ->country->toBe('ID')
        ->properties->toBe($properties);
});

test('nullable fields accept null values', function () {
    $account = new IdentityAccountData(
        type: 'BANK_ACCOUNT',
        company: 'Test Company',
        description: 'Test Description',
        country: 'ID',
        properties: ['account_number' => '1234', 'account_holder_name' => 'John']
    );

    expect(fn () => $account->setCompany(null))->not->toThrow(ValidationException::class);
    expect(fn () => $account->setDescription(null))->not->toThrow(ValidationException::class);
    expect(fn () => $account->setCountry(null))->not->toThrow(ValidationException::class);
    expect(fn () => $account->setProperties(null))->not->toThrow(ValidationException::class);

    expect($account)
        ->company->toBeNull()
        ->description->toBeNull()
        ->country->toBeNull()
        ->properties->toBeNull();
});
