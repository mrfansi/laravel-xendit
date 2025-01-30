<?php

use Mrfansi\Xendit\Data\ClientTypeData;
use Mrfansi\Xendit\Data\InvoiceParams;
use Mrfansi\Xendit\Data\InvoiceStatusData;
use Mrfansi\Xendit\Data\PaymentMethodData;
use Mrfansi\Xendit\Enums\ClientType;
use Mrfansi\Xendit\Enums\InvoiceStatus;
use Mrfansi\Xendit\Enums\PaymentMethod;
use Spatie\LaravelData\DataCollection;

test('invoice params can be created with default values', function () {
    $params = new InvoiceParams;

    expect($params)
        ->external_id->toBeNull()
        ->statuses->toBeNull()
        ->limit->toBe(10)
        ->created_after->toBeNull()
        ->created_before->toBeNull()
        ->paid_after->toBeNull()
        ->paid_before->toBeNull()
        ->expired_after->toBeNull()
        ->expired_before->toBeNull()
        ->last_invoice_id->toBeNull()
        ->client_types->toBeNull()
        ->payment_channels->toBeNull()
        ->on_demand_link->toBeNull()
        ->recurring_payment_id->toBeNull();
});

test('invoice params can be created with custom values', function () {
    $now = new DateTime;
    $tomorrow = (new DateTime)->modify('+1 day');

    $statusData = [
        InvoiceStatusData::fromEnum(InvoiceStatus::PENDING),
        InvoiceStatusData::fromEnum(InvoiceStatus::PAID),
    ];

    $clientTypeData = [
        ClientTypeData::fromEnum(ClientType::API_GATEWAY),
        ClientTypeData::fromEnum(ClientType::DASHBOARD),
    ];

    $paymentMethodData = [
        PaymentMethodData::fromEnum(PaymentMethod::OVO),
        PaymentMethodData::fromEnum(PaymentMethod::DANA),
    ];

    $params = new InvoiceParams(
        external_id: 'inv-123',
        statuses: new DataCollection(InvoiceStatusData::class, $statusData),
        limit: 20,
        created_after: $now,
        created_before: $tomorrow,
        client_types: new DataCollection(ClientTypeData::class, $clientTypeData),
        payment_channels: new DataCollection(PaymentMethodData::class, $paymentMethodData)
    );

    expect($params)
        ->external_id->toBe('inv-123')
        ->limit->toBe(20)
        ->created_after->toBe($now)
        ->created_before->toBe($tomorrow)
        ->and($params->statuses)->toBeInstanceOf(DataCollection::class)
        ->and($params->client_types)->toBeInstanceOf(DataCollection::class)
        ->and($params->payment_channels)->toBeInstanceOf(DataCollection::class);

    // Only check items if the collections are not null
    if ($params->statuses) {
        $items = $params->statuses->items();
        expect($items)->toHaveCount(2);
        expect($items[0]->status)->toEqual(InvoiceStatus::PENDING);
        expect($items[1]->status)->toEqual(InvoiceStatus::PAID);
    }

    if ($params->client_types) {
        $items = $params->client_types->items();
        expect($items)->toHaveCount(2);
        expect($items[0]->type)->toEqual(ClientType::API_GATEWAY);
        expect($items[1]->type)->toEqual(ClientType::DASHBOARD);
    }

    if ($params->payment_channels) {
        $items = $params->payment_channels->items();
        expect($items)->toHaveCount(2);
        expect($items[0]->method)->toEqual(PaymentMethod::OVO);
        expect($items[1]->method)->toEqual(PaymentMethod::DANA);
    }
});

test('invoice params validates limit range', function () {
    // Too low
    expect(fn () => new InvoiceParams(limit: 0))
        ->toThrow(InvalidArgumentException::class, 'Limit must be between 1 and 100');

    // Too high
    expect(fn () => new InvoiceParams(limit: 101))
        ->toThrow(InvalidArgumentException::class, 'Limit must be between 1 and 100');

    // Valid values
    expect(fn () => new InvoiceParams(limit: 1))->not->toThrow(InvalidArgumentException::class);
    expect(fn () => new InvoiceParams(limit: 50))->not->toThrow(InvalidArgumentException::class);
    expect(fn () => new InvoiceParams(limit: 100))->not->toThrow(InvalidArgumentException::class);
});

test('invoice params validates created date range', function () {
    $now = new DateTime;
    $tomorrow = (new DateTime)->modify('+1 day');
    $yesterday = (new DateTime)->modify('-1 day');

    // Missing one date
    expect(fn () => new InvoiceParams(created_after: $now))
        ->toThrow(InvalidArgumentException::class, 'Both created_after and created_before must be provided together');

    expect(fn () => new InvoiceParams(created_before: $now))
        ->toThrow(InvalidArgumentException::class, 'Both created_after and created_before must be provided together');

    // Invalid range (after > before)
    expect(fn () => new InvoiceParams(
        created_after: $tomorrow,
        created_before: $yesterday
    ))->toThrow(InvalidArgumentException::class, 'created_after must be before created_before');

    // Valid range
    expect(fn () => new InvoiceParams(
        created_after: $yesterday,
        created_before: $tomorrow
    ))->not->toThrow(InvalidArgumentException::class);
});

test('invoice params validates paid date range', function () {
    $now = new DateTime;
    $tomorrow = (new DateTime)->modify('+1 day');
    $yesterday = (new DateTime)->modify('-1 day');

    // Missing one date
    expect(fn () => new InvoiceParams(paid_after: $now))
        ->toThrow(InvalidArgumentException::class, 'Both paid_after and paid_before must be provided together');

    expect(fn () => new InvoiceParams(paid_before: $now))
        ->toThrow(InvalidArgumentException::class, 'Both paid_after and paid_before must be provided together');

    // Invalid range (after > before)
    expect(fn () => new InvoiceParams(
        paid_after: $tomorrow,
        paid_before: $yesterday
    ))->toThrow(InvalidArgumentException::class, 'paid_after must be before paid_before');

    // Valid range
    expect(fn () => new InvoiceParams(
        paid_after: $yesterday,
        paid_before: $tomorrow
    ))->not->toThrow(InvalidArgumentException::class);
});

test('invoice params validates expired date range', function () {
    $now = new DateTime;
    $tomorrow = (new DateTime)->modify('+1 day');
    $yesterday = (new DateTime)->modify('-1 day');

    // Missing one date
    expect(fn () => new InvoiceParams(expired_after: $now))
        ->toThrow(InvalidArgumentException::class, 'Both expired_after and expired_before must be provided together');

    expect(fn () => new InvoiceParams(expired_before: $now))
        ->toThrow(InvalidArgumentException::class, 'Both expired_after and expired_before must be provided together');

    // Invalid range (after > before)
    expect(fn () => new InvoiceParams(
        expired_after: $tomorrow,
        expired_before: $yesterday
    ))->toThrow(InvalidArgumentException::class, 'expired_after must be before expired_before');

    // Valid range
    expect(fn () => new InvoiceParams(
        expired_after: $yesterday,
        expired_before: $tomorrow
    ))->not->toThrow(InvalidArgumentException::class);
});

test('invoice params can be converted to array', function () {
    $now = new DateTime;
    $tomorrow = (new DateTime)->modify('+1 day');

    $params = new InvoiceParams(
        external_id: 'inv-123',
        statuses: new DataCollection(
            InvoiceStatusData::class,
            [
                InvoiceStatusData::fromEnum(InvoiceStatus::PENDING),
                InvoiceStatusData::fromEnum(InvoiceStatus::PAID),
            ]
        ),
        limit: 20,
        created_after: $now,
        created_before: $tomorrow,
        client_types: new DataCollection(
            ClientTypeData::class,
            [
                ClientTypeData::fromEnum(ClientType::API_GATEWAY),
            ]
        ),
        payment_channels: new DataCollection(
            PaymentMethodData::class,
            [
                PaymentMethodData::fromEnum(PaymentMethod::OVO),
            ]
        )
    );

    $array = $params->all();

    expect($array)
        ->toBeArray()
        ->toHaveKeys([
            'external_id',
            'statuses',
            'limit',
            'created_after',
            'created_before',
            'client_types',
            'payment_channels',
        ]);

    expect($array['external_id'])->toBe('inv-123');
    expect($array['limit'])->toBe(20);
    expect($array['created_after'])->toBe($now);
    expect($array['created_before'])->toBe($tomorrow);

    // Collections should be converted to arrays of enum values
    expect($array['statuses'])
        ->toBeArray()
        ->toHaveCount(2)
        ->sequence(
            InvoiceStatus::PENDING->value,
            InvoiceStatus::PAID->value
        );

    expect($array['client_types'])
        ->toBeArray()
        ->toHaveCount(1)
        ->sequence(
            ClientType::API_GATEWAY->value
        );

    expect($array['payment_channels'])
        ->toBeArray()
        ->toHaveCount(1)
        ->sequence(
            PaymentMethod::OVO->value
        );
});

test('invoice params can be converted to query parameters', function () {
    $now = new DateTime;
    $tomorrow = (new DateTime)->modify('+1 day');

    $params = new InvoiceParams(
        external_id: 'inv-123',
        statuses: new DataCollection(
            InvoiceStatusData::class,
            [
                InvoiceStatusData::fromEnum(InvoiceStatus::PENDING),
                InvoiceStatusData::fromEnum(InvoiceStatus::PAID),
            ]
        ),
        limit: 20,
        created_after: $now,
        created_before: $tomorrow,
        client_types: new DataCollection(
            ClientTypeData::class,
            [
                ClientTypeData::fromEnum(ClientType::API_GATEWAY),
            ]
        ),
        payment_channels: new DataCollection(
            PaymentMethodData::class,
            [
                PaymentMethodData::fromEnum(PaymentMethod::OVO),
            ]
        )
    );

    $queryParams = $params->all();

    expect($queryParams)
        ->toBeArray()
        ->toHaveKeys([
            'external_id',
            'statuses',
            'limit',
            'created_after',
            'created_before',
            'client_types',
            'payment_channels'
        ])
        ->external_id->toBe('inv-123')
        ->limit->toBe(20)
        ->created_after->toBe($now->format('c'))
        ->created_before->toBe($tomorrow->format('c'))
        ->statuses->toBe([InvoiceStatus::PENDING->value, InvoiceStatus::PAID->value])
        ->client_types->toBe([ClientType::API_GATEWAY->value])
        ->payment_channels->toBe([PaymentMethod::OVO->value]);
});
