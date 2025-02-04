<?php

use Mrfansi\LaravelXendit\Data\Invoice\InvoiceCustomerData;
use Mrfansi\LaravelXendit\Data\Invoice\InvoiceResponse;
use Mrfansi\LaravelXendit\Enums\Currency;
use Mrfansi\LaravelXendit\Enums\InvoiceStatus;
use Mrfansi\LaravelXendit\Enums\PaymentMethod;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;

test('can create minimal invoice response', function () {
    $invoice = new InvoiceResponse(
        id: 'inv_123',
        userId: 'user_123',
        externalId: 'ext_123',
        status: InvoiceStatus::PENDING->value,
        merchantName: 'Test Merchant',
        amount: 100000
    );

    expect($invoice)
        ->toBeInstanceOf(InvoiceResponse::class)
        ->id->toBe('inv_123')
        ->userId->toBe('user_123')
        ->externalId->toBe('ext_123')
        ->status->toBe(InvoiceStatus::PENDING->value)
        ->merchantName->toBe('Test Merchant')
        ->amount->toBe(100000);
});

test('can create full invoice response', function () {
    $customer = new InvoiceCustomerData(
        givenNames: 'John',
        surname: 'Doe',
        email: 'john@example.com',
        mobileNumber: '+6281234567890'
    );

    $invoice = new InvoiceResponse(
        id: 'inv_123',
        userId: 'user_123',
        externalId: 'ext_123',
        status: InvoiceStatus::PAID->value,
        merchantName: 'Test Merchant',
        amount: 100000,
        description: 'Test Invoice',
        customer: $customer,
        merchantProfilePictureUrl: 'https://example.com/picture.jpg',
        payerEmail: 'payer@example.com',
        invoiceUrl: 'https://example.com/invoice',
        availableBanks: [
            ['bank_code' => 'BCA', 'collection_type' => 'POOL'],
        ],
        availableRetailOutlets: [
            ['name' => 'ALFAMART'],
        ],
        shouldExcludeCreditCard: true,
        shouldSendEmail: true,
        created: '2024-01-01T00:00:00Z',
        updated: '2024-01-01T00:00:00Z',
        paidAt: '2024-01-01T00:00:00Z',
        creditCardChargeId: 'charge_123',
        paymentMethod: PaymentMethod::BCA->value,
        paymentChannel: 'BCA',
        paymentDestination: '1234567890',
        fixedVa: true,
        paymentDetails: [
            'receipt_id' => '120318237',
            'source' => 'OVO',
        ]
    );

    expect($invoice)
        ->toBeInstanceOf(InvoiceResponse::class)
        ->id->toBe('inv_123')
        ->userId->toBe('user_123')
        ->externalId->toBe('ext_123')
        ->status->toBe(InvoiceStatus::PAID->value)
        ->merchantName->toBe('Test Merchant')
        ->amount->toBe(100000)
        ->description->toBe('Test Invoice')
        ->customer->toBeInstanceOf(InvoiceCustomerData::class)
        ->merchantProfilePictureUrl->toBe('https://example.com/picture.jpg')
        ->payerEmail->toBe('payer@example.com')
        ->invoiceUrl->toBe('https://example.com/invoice')
        ->availableBanks->toBe([
            ['bank_code' => 'BCA', 'collection_type' => 'POOL'],
        ])
        ->availableRetailOutlets->toBe([
            ['name' => 'ALFAMART'],
        ])
        ->shouldExcludeCreditCard->toBeTrue()
        ->shouldSendEmail->toBeTrue()
        ->created->toBe('2024-01-01T00:00:00Z')
        ->updated->toBe('2024-01-01T00:00:00Z')
        ->paidAt->toBe('2024-01-01T00:00:00Z')
        ->creditCardChargeId->toBe('charge_123')
        ->paymentMethod->toBe(PaymentMethod::BCA->value)
        ->paymentChannel->toBe('BCA')
        ->paymentDestination->toBe('1234567890')
        ->fixedVa->toBeTrue()
        ->paymentDetails->toBe([
            'receipt_id' => '120318237',
            'source' => 'OVO',
        ]);
});

test('fromArray creates instance with correct values', function () {
    $data = [
        'id' => 'inv_123',
        'user_id' => 'user_123',
        'external_id' => 'ext_123',
        'status' => InvoiceStatus::PENDING->value,
        'merchant_name' => 'Test Merchant',
        'amount' => 100000,
        'description' => 'Test Invoice',
        'currency' => Currency::IDR->value,
        'merchant_profile_picture_url' => 'https://example.com/picture.jpg',
        'payer_email' => 'payer@example.com',
        'invoice_url' => 'https://example.com/invoice',
        'payment_method' => PaymentMethod::BCA->value,
    ];

    $invoice = InvoiceResponse::fromArray($data);

    expect($invoice)
        ->toBeInstanceOf(InvoiceResponse::class)
        ->id->toBe('inv_123')
        ->userId->toBe('user_123')
        ->externalId->toBe('ext_123')
        ->status->toBe(InvoiceStatus::PENDING->value)
        ->merchantName->toBe('Test Merchant')
        ->amount->toBe(100000)
        ->description->toBe('Test Invoice')
        ->currency->toBe(Currency::IDR)
        ->merchantProfilePictureUrl->toBe('https://example.com/picture.jpg')
        ->payerEmail->toBe('payer@example.com')
        ->invoiceUrl->toBe('https://example.com/invoice')
        ->paymentMethod->toBe(PaymentMethod::BCA->value);
});

test('validation fails with invalid id', function () {
    expect(fn () => new InvoiceResponse(
        id: '',
        userId: 'user_123',
        externalId: 'ext_123',
        status: InvoiceStatus::PENDING->value,
        merchantName: 'Test Merchant',
        amount: 100000
    ))->toThrow(ValidationException::class, 'Invoice ID is required');
});

test('validation fails with invalid status', function () {
    expect(fn () => new InvoiceResponse(
        id: 'inv_123',
        userId: 'user_123',
        externalId: 'ext_123',
        status: 'INVALID',
        merchantName: 'Test Merchant',
        amount: 100000
    ))->toThrow(ValidationException::class, 'Invalid invoice status: INVALID');
});

test('validation fails with invalid merchant profile picture url', function () {
    expect(fn () => new InvoiceResponse(
        id: 'inv_123',
        userId: 'user_123',
        externalId: 'ext_123',
        status: InvoiceStatus::PENDING->value,
        merchantName: 'Test Merchant',
        amount: 100000,
        merchantProfilePictureUrl: 'invalid-url'
    ))->toThrow(ValidationException::class, 'Invalid merchant profile picture URL');
});

test('toArray includes only non-null values', function () {
    $invoice = new InvoiceResponse(
        id: 'inv_123',
        userId: 'user_123',
        externalId: 'ext_123',
        status: InvoiceStatus::PENDING->value,
        merchantName: 'Test Merchant',
        amount: 100000,
        description: 'Test Invoice',
        currency: Currency::IDR
    );

    expect($invoice->toArray())
        ->toBe([
            'id' => 'inv_123',
            'user_id' => 'user_123',
            'external_id' => 'ext_123',
            'status' => InvoiceStatus::PENDING->value,
            'merchant_name' => 'Test Merchant',
            'amount' => 100000,
            'description' => 'Test Invoice',
            'currency' => 'IDR',
        ])
        ->not->toHaveKeys([
            'customer',
            'merchant_profile_picture_url',
            'payer_email',
            'invoice_url',
            'available_banks',
            'available_retail_outlets',
            'should_exclude_credit_card',
            'should_send_email',
            'created',
            'updated',
            'paid_at',
            'credit_card_charge_id',
            'payment_method',
            'payment_channel',
            'payment_destination',
            'fixed_va',
            'payment_details',
        ]);
});
