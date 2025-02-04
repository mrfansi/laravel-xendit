<?php

use Mrfansi\LaravelXendit\Data\Invoice\InvoiceCustomerData;
use Mrfansi\LaravelXendit\Data\Invoice\InvoiceData;
use Mrfansi\LaravelXendit\Enums\Currency;
use Mrfansi\LaravelXendit\Enums\Locale;
use Mrfansi\LaravelXendit\Enums\ReminderTimeUnit;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;

test('can create minimal invoice', function () {
    $invoice = new InvoiceData(
        externalId: 'invoice-123',
        amount: 100000
    );

    expect($invoice)
        ->toBeInstanceOf(InvoiceData::class)
        ->externalId->toBe('invoice-123')
        ->amount->toBe(100000);
});

test('can create full invoice', function () {
    $customer = new InvoiceCustomerData(
        givenNames: 'John',
        surname: 'Doe',
        email: 'john@example.com',
        mobileNumber: '+6281234567890'
    );

    $invoice = new InvoiceData(
        externalId: 'invoice-123',
        amount: 100000,
        description: 'Test Invoice',
        customer: $customer,
        customerNotificationPreference: [
            'invoice_created' => ['email', 'whatsapp'],
            'invoice_paid' => ['email'],
        ],
        invoiceDuration: 86400,
        successRedirectUrl: 'https://example.com/success',
        failureRedirectUrl: 'https://example.com/failure',
        paymentMethods: ['CREDIT_CARD', 'BCA'],
        currency: Currency::IDR,
        callbackVirtualAccountId: 'va-123',
        midLabel: 'mid-123',
        reminderTimeUnit: ReminderTimeUnit::DAYS,
        reminderTime: 1,
        locale: Locale::ENGLISH,
        items: [
            [
                'name' => 'Item 1',
                'quantity' => 1,
                'price' => 50000,
            ],
            [
                'name' => 'Item 2',
                'quantity' => 1,
                'price' => 50000,
            ],
        ],
        fees: [
            [
                'type' => 'admin',
                'value' => 1000,
            ],
        ],
        shouldAuthenticateCreditCard: true,
        channelProperties: [
            'allowed_bins' => ['123456'],
        ],
        metadata: [
            'order_id' => '123',
        ]
    );

    expect($invoice)
        ->toBeInstanceOf(InvoiceData::class)
        ->externalId->toBe('invoice-123')
        ->amount->toBe(100000)
        ->description->toBe('Test Invoice')
        ->customer->toBeInstanceOf(InvoiceCustomerData::class)
        ->customerNotificationPreference->toBe([
            'invoice_created' => ['email', 'whatsapp'],
            'invoice_paid' => ['email'],
        ])
        ->invoiceDuration->toBe(86400)
        ->successRedirectUrl->toBe('https://example.com/success')
        ->failureRedirectUrl->toBe('https://example.com/failure')
        ->paymentMethods->toBe(['CREDIT_CARD', 'BCA'])
        ->currency->toBe(Currency::IDR)
        ->callbackVirtualAccountId->toBe('va-123')
        ->midLabel->toBe('mid-123')
        ->reminderTimeUnit->toBe(ReminderTimeUnit::DAYS)
        ->reminderTime->toBe(1)
        ->locale->toBe(Locale::ENGLISH)
        ->items->toHaveCount(2)
        ->fees->toHaveCount(1)
        ->shouldAuthenticateCreditCard->toBeTrue()
        ->channelProperties->toBe(['allowed_bins' => ['123456']])
        ->metadata->toBe(['order_id' => '123']);
});

test('validation fails with invalid external id', function () {
    expect(fn () => new InvoiceData(
        externalId: '',
        amount: 100000
    ))->toThrow(ValidationException::class, 'External ID must be between 1 and 255 characters');
});

test('validation fails with invalid amount', function () {
    expect(fn () => new InvoiceData(
        externalId: 'invoice-123',
        amount: 0
    ))->toThrow(ValidationException::class, 'Amount must be greater than 0');
});

test('validation fails with invalid invoice duration', function () {
    expect(fn () => new InvoiceData(
        externalId: 'invoice-123',
        amount: 100000,
        invoiceDuration: 0
    ))->toThrow(ValidationException::class, 'Invoice duration must be between 1 and 31536000 seconds');
});

test('validation fails with invalid notification preference', function () {
    expect(fn () => new InvoiceData(
        externalId: 'invoice-123',
        amount: 100000,
        customerNotificationPreference: [
            'invalid_type' => ['email'],
        ]
    ))->toThrow(ValidationException::class, 'Invalid notification type: invalid_type');
});

test('validation fails with invalid notification channel', function () {
    expect(fn () => new InvoiceData(
        externalId: 'invoice-123',
        amount: 100000,
        customerNotificationPreference: [
            'invoice_created' => ['invalid_channel'],
        ]
    ))->toThrow(ValidationException::class, 'Invalid channel: invalid_channel');
});

test('toArray includes only non-null values', function () {
    $invoice = new InvoiceData(
        externalId: 'invoice-123',
        amount: 100000,
        description: 'Test Invoice',
        currency: Currency::IDR
    );

    expect($invoice->toArray())
        ->toBe([
            'external_id' => 'invoice-123',
            'amount' => 100000,
            'description' => 'Test Invoice',
            'currency' => 'IDR',
        ])
        ->not->toHaveKeys([
            'customer',
            'customer_notification_preference',
            'invoice_duration',
            'success_redirect_url',
            'failure_redirect_url',
            'payment_methods',
            'callback_virtual_account_id',
            'mid_label',
            'reminder_time_unit',
            'reminder_time',
            'locale',
            'items',
            'fees',
            'should_authenticate_credit_card',
            'channel_properties',
            'metadata',
        ]);
});

test('fromArray creates instance with correct values', function () {
    $data = [
        'external_id' => 'invoice-123',
        'amount' => 100000,
        'description' => 'Test Invoice',
        'currency' => 'IDR',
    ];

    $invoice = InvoiceData::fromArray($data);

    expect($invoice)
        ->toBeInstanceOf(InvoiceData::class)
        ->externalId->toBe('invoice-123')
        ->amount->toBe(100000)
        ->description->toBe('Test Invoice')
        ->currency->toBe(Currency::IDR);
});
