<?php

use Mrfansi\XenditSdk\Enums\CountryCode;
use Mrfansi\XenditSdk\Enums\Currency;

test('currency has correct values', function () {
    expect(Currency::IDR->value)->toBe('IDR')
        ->and(Currency::PHP->value)->toBe('PHP')
        ->and(Currency::THB->value)->toBe('THB')
        ->and(Currency::VND->value)->toBe('VND')
        ->and(Currency::MYR->value)->toBe('MYR');
});

test('get currency by country returns correct currency', function () {
    expect(Currency::getCurrencyByCountry(CountryCode::INDONESIA))->toBe(Currency::IDR)
        ->and(Currency::getCurrencyByCountry(CountryCode::PHILIPPINES))->toBe(Currency::PHP)
        ->and(Currency::getCurrencyByCountry(CountryCode::THAILAND))->toBe(Currency::THB)
        ->and(Currency::getCurrencyByCountry(CountryCode::VIETNAM))->toBe(Currency::VND)
        ->and(Currency::getCurrencyByCountry(CountryCode::MALAYSIA))->toBe(Currency::MYR);
});
