<?php

use Mrfansi\LaravelXendit\Data\Invoice\InvoiceParams;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;

test('can create minimal invoice params', function () {
    $params = new InvoiceParams;

    expect($params)
        ->toBeInstanceOf(InvoiceParams::class)
        ->limit->toBe(10)
        ->statuses->toBe([])
        ->clientTypes->toBe([])
        ->paymentChannels->toBe([]);
});

test('can create full invoice params', function () {
    $params = new InvoiceParams(
        externalId: 'invoice-123',
        statuses: ['PENDING', 'PAID'],
        limit: 50,
        createdAfter: '2024-01-01T00:00:00Z',
        createdBefore: '2024-01-31T23:59:59Z',
        paidAfter: '2024-01-01T00:00:00Z',
        paidBefore: '2024-01-31T23:59:59Z',
        expiredAfter: '2024-01-01T00:00:00Z',
        expiredBefore: '2024-01-31T23:59:59Z',
        lastInvoiceId: 'last-invoice-123',
        clientTypes: ['API_GATEWAY', 'DASHBOARD'],
        paymentChannels: ['CREDIT_CARD', 'BCA'],
        onDemandLink: 'https://example.com/demand',
        recurringPaymentId: 'recurring-123'
    );

    expect($params)
        ->toBeInstanceOf(InvoiceParams::class)
        ->externalId->toBe('invoice-123')
        ->statuses->toBe(['PENDING', 'PAID'])
        ->limit->toBe(50)
        ->createdAfter->toBe('2024-01-01T00:00:00Z')
        ->createdBefore->toBe('2024-01-31T23:59:59Z')
        ->paidAfter->toBe('2024-01-01T00:00:00Z')
        ->paidBefore->toBe('2024-01-31T23:59:59Z')
        ->expiredAfter->toBe('2024-01-01T00:00:00Z')
        ->expiredBefore->toBe('2024-01-31T23:59:59Z')
        ->lastInvoiceId->toBe('last-invoice-123')
        ->clientTypes->toBe(['API_GATEWAY', 'DASHBOARD'])
        ->paymentChannels->toBe(['CREDIT_CARD', 'BCA'])
        ->onDemandLink->toBe('https://example.com/demand')
        ->recurringPaymentId->toBe('recurring-123');
});

test('validation fails with invalid limit', function () {
    expect(fn () => new InvoiceParams(limit: 0))
        ->toThrow(ValidationException::class, 'Limit must be between 1 and 100');

    expect(fn () => new InvoiceParams(limit: 101))
        ->toThrow(ValidationException::class, 'Limit must be between 1 and 100');
});

test('validation fails with invalid status', function () {
    expect(fn () => new InvoiceParams(statuses: ['INVALID']))
        ->toThrow(ValidationException::class, 'Invalid status: INVALID');
});

test('validation fails with invalid client type', function () {
    expect(fn () => new InvoiceParams(clientTypes: ['INVALID']))
        ->toThrow(ValidationException::class, 'Invalid client type: INVALID');
});

test('validation fails when created_after provided without created_before', function () {
    expect(fn () => new InvoiceParams(createdAfter: '2024-01-01T00:00:00Z'))
        ->toThrow(ValidationException::class, 'Both created_after and created_before must be provided together');
});

test('validation fails when created_before provided without created_after', function () {
    expect(fn () => new InvoiceParams(createdBefore: '2024-01-01T00:00:00Z'))
        ->toThrow(ValidationException::class, 'Both created_after and created_before must be provided together');
});

test('validation fails with invalid date format', function () {
    expect(fn () => new InvoiceParams(
        createdAfter: 'invalid-date',
        createdBefore: '2024-01-01T00:00:00Z'
    ))->toThrow(ValidationException::class, 'Invalid ISO 8601 date format for created range');
});

test('validation fails when after date is later than before date', function () {
    expect(fn () => new InvoiceParams(
        createdAfter: '2024-02-01T00:00:00Z',
        createdBefore: '2024-01-01T00:00:00Z'
    ))->toThrow(ValidationException::class, 'Invalid ISO 8601 date format for created range');
});

test('toArray includes only non-null values', function () {
    $params = new InvoiceParams(
        externalId: 'invoice-123',
        limit: 50
    );

    expect($params->toArray())
        ->toBe([
            'external_id' => 'invoice-123',
            'limit' => 50,
        ])
        ->not->toHaveKeys([
            'statuses',
            'created_after',
            'created_before',
            'paid_after',
            'paid_before',
            'expired_after',
            'expired_before',
            'last_invoice_id',
            'client_types',
            'payment_channels',
            'on_demand_link',
            'recurring_payment_id',
        ]);
});

test('fromArray creates instance with correct values', function () {
    $data = [
        'external_id' => 'invoice-123',
        'limit' => 50,
        'statuses' => ['PENDING', 'PAID'],
        'client_types' => ['API_GATEWAY'],
    ];

    $params = InvoiceParams::fromArray($data);

    expect($params)
        ->toBeInstanceOf(InvoiceParams::class)
        ->externalId->toBe('invoice-123')
        ->limit->toBe(50)
        ->statuses->toBe(['PENDING', 'PAID'])
        ->clientTypes->toBe(['API_GATEWAY']);
});
