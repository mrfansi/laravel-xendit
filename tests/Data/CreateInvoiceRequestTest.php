<?php

use Mrfansi\XenditSdk\Data\CardChannel\CardChannelProperties;
use Mrfansi\XenditSdk\Data\CardChannel\InstallmentConfiguration;
use Mrfansi\XenditSdk\Data\CreateInvoiceRequest;
use Mrfansi\XenditSdk\Data\Customer;
use Mrfansi\XenditSdk\Data\Fee;
use Mrfansi\XenditSdk\Data\Item;
use Mrfansi\XenditSdk\Data\NotificationPreference;
use Mrfansi\XenditSdk\Enums\Currency;
use Mrfansi\XenditSdk\Enums\Locale;
use Mrfansi\XenditSdk\Enums\PaymentMethod;
use Mrfansi\XenditSdk\Enums\ReminderTimeUnit;
use Spatie\LaravelData\DataCollection;

test('create invoice request can be created with minimum required fields', function () {
    $request = new CreateInvoiceRequest(
        external_id: 'invoice-123',
        amount: 100000
    );

    expect($request)
        ->external_id->toBe('invoice-123')
        ->amount->toBe(100000.0)
        ->description->toBeNull()
        ->customer->toBeNull()
        ->payment_methods->toBeNull();
});

test('create invoice request can be created with all fields', function () {
    $request = new CreateInvoiceRequest(
        external_id: 'invoice-123',
        amount: 100000,
        description: 'Test invoice',
        customer: new Customer(
            given_names: 'John',
            surname: 'Doe',
            email: 'john@example.com',
            mobile_number: '+6281234567890',
            addresses: null
        ),
        customer_notification_preference: new NotificationPreference,
        invoice_duration: 86400,
        success_redirect_url: 'https://example.com/success',
        failure_redirect_url: 'https://example.com/failure',
        payment_methods: new DataCollection(PaymentMethod::class, [
            PaymentMethod::CREDIT_CARD,
            PaymentMethod::OVO,
        ]),
        currency: Currency::IDR,
        reminder_time_unit: ReminderTimeUnit::DAYS,
        reminder_time: 1,
        locale: Locale::ENGLISH,
        items: new DataCollection(Item::class, [
            new Item('Product 1', 1, 50000),
            new Item('Product 2', 1, 50000),
        ]),
        fees: new DataCollection(Fee::class, [
            new Fee('admin', 5000),
        ]),
        should_authenticate_credit_card: true,
        channel_properties: new CardChannelProperties(
            allowed_bins: ['123456'],
            installment_configuration: new InstallmentConfiguration
        ),
        metadata: ['order_id' => '123']
    );

    expect($request)
        ->external_id->toBe('invoice-123')
        ->amount->toBe(100000.0)
        ->description->toBe('Test invoice')
        ->customer->toBeInstanceOf(Customer::class)
        ->customer_notification_preference->toBeInstanceOf(NotificationPreference::class)
        ->invoice_duration->toBe(86400)
        ->success_redirect_url->toBe('https://example.com/success')
        ->failure_redirect_url->toBe('https://example.com/failure')
        ->payment_methods->toBeInstanceOf(DataCollection::class)
        ->currency->toBe(Currency::IDR)
        ->reminder_time_unit->toBe(ReminderTimeUnit::DAYS)
        ->reminder_time->toBe(1)
        ->locale->toBe(Locale::ENGLISH)
        ->items->toBeInstanceOf(DataCollection::class)
        ->fees->toBeInstanceOf(DataCollection::class)
        ->should_authenticate_credit_card->toBeTrue()
        ->channel_properties->toBeInstanceOf(CardChannelProperties::class)
        ->metadata->toBe(['order_id' => '123']);
});

test('validates external_id length', function () {
    expect(fn () => new CreateInvoiceRequest(
        external_id: '',
        amount: 100000
    ))->toThrow(InvalidArgumentException::class, 'external_id must be between 1 and 255 characters');

    expect(fn () => new CreateInvoiceRequest(
        external_id: str_repeat('a', 256),
        amount: 100000
    ))->toThrow(InvalidArgumentException::class, 'external_id must be between 1 and 255 characters');
});

test('validates redirect URLs', function () {
    // Invalid success URL
    expect(fn () => new CreateInvoiceRequest(
        external_id: 'invoice-123',
        amount: 100000,
        success_redirect_url: 'invalid-url'
    ))->toThrow(InvalidArgumentException::class, 'success_redirect_url must be a valid URL');

    // Invalid failure URL
    expect(fn () => new CreateInvoiceRequest(
        external_id: 'invoice-123',
        amount: 100000,
        failure_redirect_url: 'invalid-url'
    ))->toThrow(InvalidArgumentException::class, 'failure_redirect_url must be a valid URL');

    // URL too long
    expect(fn () => new CreateInvoiceRequest(
        external_id: 'invoice-123',
        amount: 100000,
        success_redirect_url: 'https://example.com/'.str_repeat('a', 256)
    ))->toThrow(InvalidArgumentException::class, 'success_redirect_url must be between 1 and 255 characters');
});

test('validates invoice duration', function () {
    // Too short
    expect(fn () => new CreateInvoiceRequest(
        external_id: 'invoice-123',
        amount: 100000,
        invoice_duration: 0
    ))->toThrow(InvalidArgumentException::class, 'invoice_duration must be between 1 and 31536000 seconds');

    // Too long
    expect(fn () => new CreateInvoiceRequest(
        external_id: 'invoice-123',
        amount: 100000,
        invoice_duration: 31536001
    ))->toThrow(InvalidArgumentException::class, 'invoice_duration must be between 1 and 31536000 seconds');
});

test('validates reminder time', function () {
    // Missing unit
    expect(fn () => new CreateInvoiceRequest(
        external_id: 'invoice-123',
        amount: 100000,
        reminder_time: 1
    ))->toThrow(InvalidArgumentException::class, 'reminder_time_unit is required when reminder_time is set');

    // Invalid days value
    expect(fn () => new CreateInvoiceRequest(
        external_id: 'invoice-123',
        amount: 100000,
        reminder_time: 31,
        reminder_time_unit: ReminderTimeUnit::DAYS
    ))->toThrow(InvalidArgumentException::class);

    // Invalid hours value
    expect(fn () => new CreateInvoiceRequest(
        external_id: 'invoice-123',
        amount: 100000,
        reminder_time: 25,
        reminder_time_unit: ReminderTimeUnit::HOURS
    ))->toThrow(InvalidArgumentException::class);
});

test('validates metadata constraints', function () {
    // Too many keys
    $metadata = array_fill(0, 51, 'value');
    expect(fn () => new CreateInvoiceRequest(
        external_id: 'invoice-123',
        amount: 100000,
        metadata: $metadata
    ))->toThrow(InvalidArgumentException::class, 'metadata cannot have more than 50 keys');

    // Key too long
    expect(fn () => new CreateInvoiceRequest(
        external_id: 'invoice-123',
        amount: 100000,
        metadata: [str_repeat('a', 41) => 'value']
    ))->toThrow(InvalidArgumentException::class, 'metadata keys cannot exceed 40 characters');

    // Value too long
    expect(fn () => new CreateInvoiceRequest(
        external_id: 'invoice-123',
        amount: 100000,
        metadata: ['key' => str_repeat('a', 501)]
    ))->toThrow(InvalidArgumentException::class, 'metadata values cannot exceed 500 characters');
});
