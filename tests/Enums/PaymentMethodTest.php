<?php

use Mrfansi\Xendit\Enums\CountryCode;
use Mrfansi\Xendit\Enums\PaymentMethod;

test('payment method has correct values', function () {
    // Test a few key payment methods from different countries
    expect(PaymentMethod::CREDIT_CARD->value)->toBe('CREDIT_CARD')
        ->and(PaymentMethod::OVO->value)->toBe('OVO')
        ->and(PaymentMethod::GCASH->value)->toBe('GCASH')
        ->and(PaymentMethod::PROMPTPAY->value)->toBe('PROMPTPAY')
        ->and(PaymentMethod::TOUCHNGO->value)->toBe('TOUCHNGO');
});

test('get methods by country returns correct payment methods for Indonesia', function () {
    $methods = PaymentMethod::getMethodsByCountry(CountryCode::INDONESIA);

    expect($methods)->toBeArray()
        ->toContain(PaymentMethod::CREDIT_CARD)
        ->toContain(PaymentMethod::OVO)
        ->toContain(PaymentMethod::DANA)
        ->toContain(PaymentMethod::QRIS)
        ->and($methods)->toHaveCount(22); // Indonesia has 22 payment methods
});

test('get methods by country returns correct payment methods for Philippines', function () {
    $methods = PaymentMethod::getMethodsByCountry(CountryCode::PHILIPPINES);

    expect($methods)->toBeArray()
        ->toContain(PaymentMethod::CREDIT_CARD)
        ->toContain(PaymentMethod::GCASH)
        ->toContain(PaymentMethod::GRABPAY)
        ->and($methods)->toHaveCount(32); // Philippines has 32 payment methods
});

test('get methods by country returns correct payment methods for Thailand', function () {
    $methods = PaymentMethod::getMethodsByCountry(CountryCode::THAILAND);

    expect($methods)->toBeArray()
        ->toContain(PaymentMethod::CREDIT_CARD)
        ->toContain(PaymentMethod::PROMPTPAY)
        ->toContain(PaymentMethod::TRUEMONEY)
        ->and($methods)->toHaveCount(11); // Thailand has 11 payment methods
});

test('get methods by country returns correct payment methods for Vietnam', function () {
    $methods = PaymentMethod::getMethodsByCountry(CountryCode::VIETNAM);

    expect($methods)->toBeArray()
        ->toContain(PaymentMethod::CREDIT_CARD)
        ->toContain(PaymentMethod::VNPTWALLET)
        ->toContain(PaymentMethod::ZALOPAY)
        ->and($methods)->toHaveCount(10); // Vietnam has 10 payment methods
});

test('get methods by country returns correct payment methods for Malaysia', function () {
    $methods = PaymentMethod::getMethodsByCountry(CountryCode::MALAYSIA);

    expect($methods)->toBeArray()
        ->toContain(PaymentMethod::CREDIT_CARD)
        ->toContain(PaymentMethod::TOUCHNGO)
        ->toContain(PaymentMethod::DD_MAYB2U_FPX)
        ->and($methods)->toHaveCount(42); // Malaysia has 42 payment methods
});
