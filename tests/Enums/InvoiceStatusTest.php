<?php

use Mrfansi\XenditSdk\Enums\InvoiceStatus;

test('invoice status has correct values', function () {
    expect(InvoiceStatus::PENDING->value)->toBe('PENDING')
        ->and(InvoiceStatus::PAID->value)->toBe('PAID')
        ->and(InvoiceStatus::SETTLED->value)->toBe('SETTLED')
        ->and(InvoiceStatus::EXPIRED->value)->toBe('EXPIRED');
});
