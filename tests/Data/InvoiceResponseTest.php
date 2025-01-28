<?php

use Mrfansi\XenditSdk\Data\CardChannel\CardChannelProperties;
use Mrfansi\XenditSdk\Data\Customer;
use Mrfansi\XenditSdk\Data\Fee;
use Mrfansi\XenditSdk\Data\InvoiceResponse;
use Mrfansi\XenditSdk\Data\Item;
use Mrfansi\XenditSdk\Data\NotificationPreference;
use Mrfansi\XenditSdk\Data\PaymentDetails;
use Mrfansi\XenditSdk\Data\PaymentMethodData;
use Mrfansi\XenditSdk\Enums\Currency;
use Mrfansi\XenditSdk\Enums\InvoiceStatus;
use Mrfansi\XenditSdk\Enums\Locale;
use Mrfansi\XenditSdk\Enums\PaymentMethod;
use Mrfansi\XenditSdk\Enums\QrisSource;
use Spatie\LaravelData\DataCollection;

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
        paid_at: null,
        credit_card_charge_id: null,
        payment_method: null,
        payment_channel: null,
        payment_destination: null,
        fixed_va: null,
        locale: null,
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
        ->created->toBe($now);
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
        description: 'Test purchase',
        invoice_url: 'https://invoice.xendit.co/123',
        customer: new Customer(
            given_names: 'John',
            surname: 'Doe',
            email: 'john@example.com',
            mobile_number: '+6281234567890',
            addresses: null
        ),
        customer_notification_preference: new NotificationPreference,
        expiry_date: $now,
        available_banks: [
            [
                'bank_code' => 'BCA',
                'collection_type' => 'POOL',
                'transfer_amount' => 100000.0,
                'bank_branch' => 'Virtual Account',
                'account_holder_name' => 'TEST STORE',
            ],
        ],
        available_retail_outlets: [
            [
                'retail_outlet_name' => 'ALFAMART',
                'payment_code' => '12345',
                'transfer_amount' => 100000.0,
            ],
        ],
        should_exclude_credit_card: false,
        should_send_email: true,
        updated: $now,
        created: $now,
        mid_label: 'TEST_MID',
        currency: Currency::IDR,
        success_redirect_url: 'https://example.com/success',
        failure_redirect_url: 'https://example.com/failure',
        paid_at: $now,
        credit_card_charge_id: 'cc_123',
        payment_method: new DataCollection(
            PaymentMethodData::class,
            [PaymentMethodData::fromEnum(PaymentMethod::CREDIT_CARD)]
        ),
        payment_channel: new DataCollection(
            PaymentMethodData::class,
            [PaymentMethodData::fromEnum(PaymentMethod::BCA)]
        ),
        payment_destination: '12345678',
        fixed_va: true,
        locale: Locale::INDONESIAN,
        items: new DataCollection(
            Item::class,
            [new Item('Product 1', 1, 100000.0)]
        ),
        fees: new DataCollection(
            Fee::class,
            [new Fee('admin', 5000.0)]
        ),
        payment_details: new PaymentDetails(
            receipt_id: '120318237',
            source: QrisSource::OVO
        ),
        should_authenticate_credit_card: true,
        channel_properties: new CardChannelProperties,
        metadata: ['order_id' => '123']
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
        ->description->toBe('Test purchase')
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
        ->mid_label->toBe('TEST_MID')
        ->currency->toBe(Currency::IDR)
        ->success_redirect_url->toBe('https://example.com/success')
        ->failure_redirect_url->toBe('https://example.com/failure')
        ->paid_at->toBe($now)
        ->credit_card_charge_id->toBe('cc_123')
        ->payment_method->toBeInstanceOf(DataCollection::class)
        ->payment_channel->toBeInstanceOf(DataCollection::class)
        ->payment_destination->toBe('12345678')
        ->fixed_va->toBeTrue()
        ->locale->toBe(Locale::INDONESIAN)
        ->items->toBeInstanceOf(DataCollection::class)
        ->fees->toBeInstanceOf(DataCollection::class)
        ->payment_details->toBeInstanceOf(PaymentDetails::class)
        ->should_authenticate_credit_card->toBeTrue()
        ->channel_properties->toBeInstanceOf(CardChannelProperties::class)
        ->metadata->toBe(['order_id' => '123']);
});

test('invoice response can be converted to array', function () {
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
        description: 'Test purchase',
        invoice_url: 'https://invoice.xendit.co/123',
        customer: new Customer(
            given_names: 'John',
            surname: 'Doe',
            email: 'john@example.com',
            mobile_number: '+6281234567890',
            addresses: null
        ),
        customer_notification_preference: new NotificationPreference,
        expiry_date: $now,
        available_banks: [
            [
                'bank_code' => 'BCA',
                'collection_type' => 'POOL',
                'transfer_amount' => 100000.0,
                'bank_branch' => 'Virtual Account',
                'account_holder_name' => 'TEST STORE',
            ],
        ],
        available_retail_outlets: null,
        should_exclude_credit_card: false,
        should_send_email: true,
        updated: $now,
        created: $now,
        mid_label: null,
        currency: Currency::IDR,
        success_redirect_url: null,
        failure_redirect_url: null,
        paid_at: null,
        credit_card_charge_id: null,
        payment_method: null,
        payment_channel: null,
        payment_destination: null,
        fixed_va: null,
        locale: null,
        items: new DataCollection(
            Item::class,
            [new Item('Product 1', 1, 100000.0)]
        ),
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
            'currency',
            'items',
        ])
        ->and($array['id'])->toBe('inv_123')
        ->and($array['status'])->toBe('PAID')
        ->and($array['currency'])->toBe('IDR')
        ->and($array['customer'])->toBeArray()
        ->and($array['items'])->toBeArray()->toHaveCount(1);
});
