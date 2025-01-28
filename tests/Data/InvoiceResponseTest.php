<?php

use Mrfansi\XenditSdk\Data\CardChannel\CardChannelProperties;
use Mrfansi\XenditSdk\Data\Customer;
use Mrfansi\XenditSdk\Data\InvoiceResponse;
use Mrfansi\XenditSdk\Data\NotificationPreference;
use Mrfansi\XenditSdk\Data\PaymentDetails;
use Mrfansi\XenditSdk\Enums\Currency;
use Mrfansi\XenditSdk\Enums\InvoiceStatus;
use Mrfansi\XenditSdk\Enums\QrisSource;

test('invoice response can be created with minimum required fields', function () {
    $now = new DateTime;

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
        customer: null,
        customer_notification_preference: null,
        expiry_date: $now,
        available_banks: null,
        available_retail_outlets: null,
        should_exclude_credit_card: false,
        should_send_email: true,
        updated: $now,
        created: $now,
        mid_label: null,
        currency: null,
        success_redirect_url: null,
        failure_redirect_url: null,
        payment_methods: null,
        fixed_va: null,
        items: null,
        fees: null,
        payment_details: null,
        should_authenticate_credit_card: null,
        channel_properties: null,
        metadata: null
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
        ->customer->toBeNull()
        ->customer_notification_preference->toBeNull()
        ->expiry_date->toBe($now)
        ->available_banks->toBeNull()
        ->available_retail_outlets->toBeNull()
        ->should_exclude_credit_card->toBeFalse()
        ->should_send_email->toBeTrue()
        ->updated->toBe($now)
        ->created->toBe($now)
        ->mid_label->toBeNull()
        ->currency->toBeNull()
        ->success_redirect_url->toBeNull()
        ->failure_redirect_url->toBeNull()
        ->payment_methods->toBeNull()
        ->fixed_va->toBeNull()
        ->items->toBeNull()
        ->fees->toBeNull()
        ->payment_details->toBeNull()
        ->should_authenticate_credit_card->toBeNull()
        ->channel_properties->toBeNull()
        ->metadata->toBeNull();
});

test('invoice response can be created with all fields', function () {
    $now = new DateTime;

    $response = new InvoiceResponse(
        id: 'inv_123',
        user_id: 'user_123',
        external_id: 'ext_123',
        status: InvoiceStatus::PAID,
        merchant_name: 'Test Store',
        merchant_profile_picture_url: 'https://example.com/logo.png',
        amount: 100000.0,
        payer_email: 'customer@example.com',
        description: 'Test invoice',
        invoice_url: 'https://invoice.xendit.co/123',
        customer: new Customer(
            given_names: 'John',
            surname: 'Doe',
            email: 'john@example.com',
            mobile_number: '+6281234567890',
            addresses: [],
        ),
        customer_notification_preference: new NotificationPreference(
            invoice_created: ['whatsapp'],
            invoice_reminder: ['whatsapp'],
            invoice_paid: ['whatsapp'],
        ),
        expiry_date: $now,
        available_banks: [
            [
                'bank_code' => 'BCA',
                'collection_type' => 'POOL',
                'bank_branch' => 'Virtual Account',
                'bank_account_number' => '12345678',
            ],
        ],
        available_retail_outlets: [
            [
                'retail_outlet' => 'ALFAMART',
                'payment_code' => '12345678',
            ],
        ],
        should_exclude_credit_card: false,
        should_send_email: true,
        updated: $now,
        created: $now,
        mid_label: 'test_mid',
        currency: Currency::IDR,
        success_redirect_url: 'https://example.com/success',
        failure_redirect_url: 'https://example.com/failure',
        payment_methods: 'BANK_TRANSFER',
        fixed_va: ['bank_code' => 'BCA'],
        items: [
            [
                'name' => 'Test Item',
                'quantity' => 1,
                'price' => 100000.0,
            ],
        ],
        fees: [
            [
                'type' => 'ADMIN',
                'value' => 5000.0,
            ],
        ],
        payment_details: new PaymentDetails(
            receipt_id: '12345678',
            source: QrisSource::OVO,
        ),
        should_authenticate_credit_card: true,
        channel_properties: new CardChannelProperties(
            allowed_bins: ['123456'],
        ),
        metadata: ['key' => 'value']
    );

    expect($response)
        ->id->toBe('inv_123')
        ->user_id->toBe('user_123')
        ->external_id->toBe('ext_123')
        ->status->toBe(InvoiceStatus::PAID)
        ->merchant_name->toBe('Test Store')
        ->merchant_profile_picture_url->toBe('https://example.com/logo.png')
        ->amount->toBe(100000.0)
        ->payer_email->toBe('customer@example.com')
        ->description->toBe('Test invoice')
        ->invoice_url->toBe('https://invoice.xendit.co/123')
        ->customer->toBeInstanceOf(Customer::class)
        ->customer_notification_preference->toBeInstanceOf(NotificationPreference::class)
        ->expiry_date->toBe($now)
        ->available_banks->toBeArray()
        ->available_retail_outlets->toBeArray()
        ->should_exclude_credit_card->toBeFalse()
        ->should_send_email->toBeTrue()
        ->updated->toBe($now)
        ->created->toBe($now)
        ->mid_label->toBe('test_mid')
        ->currency->toBe(Currency::IDR)
        ->success_redirect_url->toBe('https://example.com/success')
        ->failure_redirect_url->toBe('https://example.com/failure')
        ->payment_methods->toBe('BANK_TRANSFER')
        ->fixed_va->toBeArray()
        ->items->toBeArray()
        ->fees->toBeArray()
        ->payment_details->toBeInstanceOf(PaymentDetails::class)
        ->should_authenticate_credit_card->toBeTrue()
        ->channel_properties->toBeInstanceOf(CardChannelProperties::class)
        ->metadata->toBeArray();
});

test('invoice response can be converted to array', function () {
    $now = new DateTime;

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
        customer: null,
        customer_notification_preference: null,
        expiry_date: $now,
        available_banks: null,
        available_retail_outlets: null,
        should_exclude_credit_card: false,
        should_send_email: true,
        updated: $now,
        created: $now,
        mid_label: null,
        currency: Currency::IDR,
        success_redirect_url: null,
        failure_redirect_url: null,
        payment_methods: null,
        fixed_va: null,
        items: null,
        fees: null,
        payment_details: null,
        should_authenticate_credit_card: null,
        channel_properties: null,
        metadata: null
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
            'customer',
            'customer_notification_preference',
            'expiry_date',
            'available_banks',
            'available_retail_outlets',
            'should_exclude_credit_card',
            'should_send_email',
            'updated',
            'created',
            'mid_label',
            'currency',
            'success_redirect_url',
            'failure_redirect_url',
            'payment_methods',
            'fixed_va',
            'items',
            'fees',
            'payment_details',
            'should_authenticate_credit_card',
            'channel_properties',
            'metadata',
        ])
        ->and($array['id'])->toBe('inv_123')
        ->and($array['user_id'])->toBe('user_123')
        ->and($array['external_id'])->toBe('ext_123')
        ->and($array['status'])->toBe('PENDING')
        ->and($array['merchant_name'])->toBe('Test Store')
        ->and($array['merchant_profile_picture_url'])->toBeNull()
        ->and($array['amount'])->toBe(100000.0)
        ->and($array['payer_email'])->toBeNull()
        ->and($array['description'])->toBeNull()
        ->and($array['invoice_url'])->toBe('https://invoice.xendit.co/123')
        ->and($array['customer'])->toBeNull()
        ->and($array['customer_notification_preference'])->toBeNull()
        ->and($array['available_banks'])->toBeNull()
        ->and($array['available_retail_outlets'])->toBeNull()
        ->and($array['should_exclude_credit_card'])->toBeFalse()
        ->and($array['should_send_email'])->toBeTrue()
        ->and($array['mid_label'])->toBeNull()
        ->and($array['currency'])->toBe('IDR')
        ->and($array['success_redirect_url'])->toBeNull()
        ->and($array['failure_redirect_url'])->toBeNull()
        ->and($array['payment_methods'])->toBeNull()
        ->and($array['fixed_va'])->toBeNull()
        ->and($array['items'])->toBeNull()
        ->and($array['fees'])->toBeNull()
        ->and($array['payment_details'])->toBeNull()
        ->and($array['should_authenticate_credit_card'])->toBeNull()
        ->and($array['channel_properties'])->toBeNull()
        ->and($array['metadata'])->toBeNull();
});
