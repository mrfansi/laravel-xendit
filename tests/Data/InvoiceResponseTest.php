<?php

use Carbon\Carbon;
use Mrfansi\Xendit\Data\CardChannel\CardChannelProperties;
use Mrfansi\Xendit\Data\Customer;
use Mrfansi\Xendit\Data\InvoiceResponse;
use Mrfansi\Xendit\Data\NotificationPreference;
use Mrfansi\Xendit\Data\PaymentDetails;
use Mrfansi\Xendit\Enums\Currency;
use Mrfansi\Xendit\Enums\InvoiceStatus;
use Mrfansi\Xendit\Enums\NotificationChannel;
use Mrfansi\Xendit\Enums\QrisSource;

test('invoice response can be created with minimum required fields', function () {
    $now = Carbon::now();

    $response = new InvoiceResponse(
        id: 'inv_123',
        user_id: 'user_123',
        external_id: 'ext_123',
        status: InvoiceStatus::PENDING,
        merchant_name: 'Test Store',
        merchant_profile_picture_url: null,
        amount: 100000.0,
        payer_email: null,
        description: null,
        invoice_url: 'https://invoice.xendit.co/123',
        expiry_date: $now,
        available_banks: null,
        available_retail_outlets: null,
        available_ewallets: null,
        available_qr_codes: null,
        available_direct_debits: null,
        available_paylaters: null,
        should_exclude_credit_card: false,
        should_send_email: true,
        created: $now,
        updated: $now,
        currency: null,
        items: null,
        customer: null
    );

    expect($response)
        ->id->toBe('inv_123')
        ->user_id->toBe('user_123')
        ->external_id->toBe('ext_123')
        ->status->toBe(InvoiceStatus::PENDING)
        ->merchant_name->toBe('Test Store')
        ->merchant_profile_picture_url->toBeNull()
        ->amount->toBe(100000.0)
        ->payer_email->toBeNull()
        ->description->toBeNull()
        ->invoice_url->toBe('https://invoice.xendit.co/123')
        ->expiry_date->toBe($now)
        ->available_banks->toBeNull()
        ->available_retail_outlets->toBeNull()
        ->available_ewallets->toBeNull()
        ->available_qr_codes->toBeNull()
        ->available_direct_debits->toBeNull()
        ->available_paylaters->toBeNull()
        ->should_exclude_credit_card->toBeFalse()
        ->should_send_email->toBeTrue()
        ->created->toBe($now)
        ->updated->toBe($now)
        ->currency->toBeNull()
        ->items->toBeNull()
        ->customer->toBeNull();
});

test('invoice response can be created from array', function () {
    $now = Carbon::now();

    $data = [
        'id' => 'inv_123',
        'user_id' => 'user_123',
        'external_id' => 'ext_123',
        'status' => InvoiceStatus::PENDING->value,
        'merchant_name' => 'Test Store',
        'merchant_profile_picture_url' => null,
        'amount' => 100000.0,
        'payer_email' => null,
        'description' => null,
        'invoice_url' => 'https://invoice.xendit.co/123',
        'expiry_date' => $now->format('c'),
        'available_banks' => null,
        'available_retail_outlets' => null,
        'available_ewallets' => null,
        'available_qr_codes' => null,
        'available_direct_debits' => null,
        'available_paylaters' => null,
        'should_exclude_credit_card' => false,
        'should_send_email' => true,
        'created' => $now->format('c'),
        'updated' => $now->format('c'),
        'currency' => Currency::IDR->value,
        'items' => null,
        'customer' => [
            'given_names' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com'
        ]
    ];

    $response = InvoiceResponse::fromArray($data);

    expect($response)
        ->id->toBe('inv_123')
        ->user_id->toBe('user_123')
        ->external_id->toBe('ext_123')
        ->status->toBe(InvoiceStatus::PENDING)
        ->merchant_name->toBe('Test Store')
        ->merchant_profile_picture_url->toBeNull()
        ->amount->toBe(100000.0)
        ->payer_email->toBeNull()
        ->description->toBeNull()
        ->invoice_url->toBe('https://invoice.xendit.co/123')
        ->expiry_date->toBeInstanceOf(Carbon::class)
        ->available_banks->toBeNull()
        ->available_retail_outlets->toBeNull()
        ->available_ewallets->toBeNull()
        ->available_qr_codes->toBeNull()
        ->available_direct_debits->toBeNull()
        ->available_paylaters->toBeNull()
        ->should_exclude_credit_card->toBeFalse()
        ->should_send_email->toBeTrue()
        ->created->toBeInstanceOf(Carbon::class)
        ->updated->toBeInstanceOf(Carbon::class)
        ->currency->toBe(Currency::IDR)
        ->items->toBeNull()
        ->customer->toBeInstanceOf(Customer::class);

    expect($response->customer)
        ->given_names->toBe('John')
        ->surname->toBe('Doe')
        ->email->toBe('john@example.com');
});

test('invoice response can handle null input', function () {
    $response = InvoiceResponse::from(null);
    expect($response)->toBeNull();
});

test('invoice response can be converted to array', function () {
    $now = Carbon::now();

    $response = new InvoiceResponse(
        id: 'inv_123',
        user_id: 'user_123',
        external_id: 'ext_123',
        status: InvoiceStatus::PENDING,
        merchant_name: 'Test Store',
        merchant_profile_picture_url: null,
        amount: 100000.0,
        payer_email: null,
        description: null,
        invoice_url: 'https://invoice.xendit.co/123',
        expiry_date: $now,
        available_banks: null,
        available_retail_outlets: null,
        available_ewallets: null,
        available_qr_codes: null,
        available_direct_debits: null,
        available_paylaters: null,
        should_exclude_credit_card: false,
        should_send_email: true,
        created: $now,
        updated: $now,
        currency: Currency::IDR,
        items: null,
        customer: new Customer(
            given_names: 'John',
            surname: 'Doe',
            email: 'john@example.com'
        )
    );

    $array = $response->toArray();

    expect($array)
        ->toBeArray()
        ->toHaveKeys([
            'id',
            'user_id',
            'external_id',
            'status',
            'merchant_name',
            'merchant_profile_picture_url',
            'amount',
            'payer_email',
            'description',
            'invoice_url',
            'expiry_date',
            'available_banks',
            'available_retail_outlets',
            'available_ewallets',
            'available_qr_codes',
            'available_direct_debits',
            'available_paylaters',
            'should_exclude_credit_card',
            'should_send_email',
            'created',
            'updated',
            'currency',
            'items',
            'customer'
        ])
        ->id->toBe('inv_123')
        ->user_id->toBe('user_123')
        ->external_id->toBe('ext_123')
        ->status->toBe(InvoiceStatus::PENDING->value)
        ->merchant_name->toBe('Test Store')
        ->merchant_profile_picture_url->toBeNull()
        ->amount->toBe(100000.0)
        ->payer_email->toBeNull()
        ->description->toBeNull()
        ->invoice_url->toBe('https://invoice.xendit.co/123')
        ->expiry_date->toBeInstanceOf(Carbon::class)
        ->available_banks->toBeNull()
        ->available_retail_outlets->toBeNull()
        ->available_ewallets->toBeNull()
        ->available_qr_codes->toBeNull()
        ->available_direct_debits->toBeNull()
        ->available_paylaters->toBeNull()
        ->should_exclude_credit_card->toBeFalse()
        ->should_send_email->toBeTrue()
        ->created->toBeInstanceOf(Carbon::class)
        ->updated->toBeInstanceOf(Carbon::class)
        ->currency->toBe(Currency::IDR->value)
        ->items->toBeNull();

    expect($array['customer'])
        ->toBeArray()
        ->toHaveKeys(['given_names', 'surname', 'email'])
        ->given_names->toBe('John')
        ->surname->toBe('Doe')
        ->email->toBe('john@example.com');
});
