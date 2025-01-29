<?php

use Mrfansi\Xendit\Data\CardChannel\AllowedTerm;
use Mrfansi\Xendit\Data\CardChannel\CardChannelProperties;
use Mrfansi\Xendit\Data\CardChannel\InstallmentConfiguration;
use Spatie\LaravelData\DataCollection;

test('card channel properties can be created with null values', function () {
    $props = new CardChannelProperties;

    expect($props)
        ->allowed_bins->toBeNull()
        ->installment_configuration->toBeNull();
});

test('card channel properties accepts valid BIN numbers', function () {
    $props = new CardChannelProperties(
        allowed_bins: ['123456', '12345678']
    );

    expect($props->allowed_bins)
        ->toBeArray()
        ->toHaveCount(2)
        ->toBe(['123456', '12345678']);
});

test('card channel properties validates 6-digit BIN', function () {
    expect(fn () => new CardChannelProperties(
        allowed_bins: ['123456']
    ))->not->toThrow(InvalidArgumentException::class);
});

test('card channel properties validates 8-digit BIN', function () {
    expect(fn () => new CardChannelProperties(
        allowed_bins: ['12345678']
    ))->not->toThrow(InvalidArgumentException::class);
});

test('card channel properties throws exception for invalid BIN length', function () {
    // Too short
    expect(fn () => new CardChannelProperties(
        allowed_bins: ['12345']
    ))->toThrow(InvalidArgumentException::class, 'Credit card BIN must be either 6 or 8 digits');

    // Too long
    expect(fn () => new CardChannelProperties(
        allowed_bins: ['123456789']
    ))->toThrow(InvalidArgumentException::class, 'Credit card BIN must be either 6 or 8 digits');

    // Non-numeric
    expect(fn () => new CardChannelProperties(
        allowed_bins: ['12345A']
    ))->toThrow(InvalidArgumentException::class, 'Credit card BIN must be either 6 or 8 digits');
});

test('card channel properties accepts installment configuration', function () {
    $terms = [
        new AllowedTerm('BCA', [3, 6, 12]),
    ];

    $installmentConfig = new InstallmentConfiguration(
        allow_installment: true,
        allow_full_payment: true,
        allowed_terms: new DataCollection(AllowedTerm::class, $terms)
    );

    $props = new CardChannelProperties(
        installment_configuration: $installmentConfig
    );

    expect($props->installment_configuration)
        ->toBeInstanceOf(InstallmentConfiguration::class)
        ->allow_installment->toBeTrue()
        ->allow_full_payment->toBeTrue();
});

test('card channel properties can be converted to array', function () {
    $terms = [
        new AllowedTerm('BCA', [3, 6, 12]),
    ];

    $installmentConfig = new InstallmentConfiguration(
        allow_installment: true,
        allow_full_payment: true,
        allowed_terms: new DataCollection(AllowedTerm::class, $terms)
    );

    $props = new CardChannelProperties(
        allowed_bins: ['123456', '12345678'],
        installment_configuration: $installmentConfig
    );

    $array = $props->all();
    $installmentConfigArray = $props->installment_configuration->all();
    $allowedTermsArray = array_map(
        fn ($term) => $term->all(),
        $props->installment_configuration->allowed_terms->items()
    );

    expect($array)
        ->toHaveKeys(['allowed_bins', 'installment_configuration'])
        ->and($array['allowed_bins'])->toBe(['123456', '12345678']);

    expect($installmentConfigArray)
        ->toHaveKeys(['allow_installment', 'allow_full_payment', 'allowed_terms'])
        ->and($installmentConfigArray['allow_installment'])->toBeTrue()
        ->and($installmentConfigArray['allow_full_payment'])->toBeTrue();

    expect($allowedTermsArray)
        ->toBeArray()
        ->toHaveCount(1);

    expect($allowedTermsArray[0])
        ->toHaveKey('issuer')
        ->toHaveKey('terms')
        ->and($allowedTermsArray[0]['issuer'])->toBe('BCA')
        ->and($allowedTermsArray[0]['terms'])->toBe([3, 6, 12]);
});
