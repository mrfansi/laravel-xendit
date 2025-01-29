<?php

use Mrfansi\Xendit\Enums\ClientType;

test('client type has correct values', function () {
    expect(ClientType::API_GATEWAY->value)->toBe('API_GATEWAY')
        ->and(ClientType::DASHBOARD->value)->toBe('DASHBOARD')
        ->and(ClientType::INTEGRATION->value)->toBe('INTEGRATION')
        ->and(ClientType::ON_DEMAND->value)->toBe('ON_DEMAND')
        ->and(ClientType::RECURRING->value)->toBe('RECURRING')
        ->and(ClientType::MOBILE->value)->toBe('MOBILE');
});
