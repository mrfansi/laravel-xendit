<?php

use Mrfansi\Xendit\Data\CardChannel\AllowedTerm;
use Mrfansi\Xendit\Data\CardChannel\InstallmentConfiguration;
use Spatie\LaravelData\DataCollection;

test('installment configuration can be created with default values', function () {
    $config = new InstallmentConfiguration;

    expect($config)
        ->allow_installment->toBeTrue()
        ->allow_full_payment->toBeTrue()
        ->allowed_terms->toBeNull();
});

test('installment configuration can be created with custom values', function () {
    $config = new InstallmentConfiguration(
        allow_installment: false,
        allow_full_payment: false
    );

    expect($config)
        ->allow_installment->toBeFalse()
        ->allow_full_payment->toBeFalse();
});

test('installment configuration accepts allowed terms collection', function () {
    $terms = [
        new AllowedTerm('BCA', [3, 6, 12]),
        new AllowedTerm('MANDIRI', [6, 12]),
    ];

    $config = new InstallmentConfiguration(
        allowed_terms: new DataCollection(AllowedTerm::class, $terms)
    );

    expect($config->allowed_terms)
        ->toBeInstanceOf(DataCollection::class)
        ->toHaveCount(2);

    expect($config->allowed_terms->items()[0])
        ->toBeInstanceOf(AllowedTerm::class)
        ->issuer->toBe('BCA');

    expect($config->allowed_terms->items()[1])
        ->issuer->toBe('MANDIRI');
});

test('installment configuration can be converted to array', function () {
    $terms = [
        new AllowedTerm('BCA', [3, 6, 12]),
        new AllowedTerm('MANDIRI', [6, 12]),
    ];

    $config = new InstallmentConfiguration(
        allow_installment: true,
        allow_full_payment: false,
        allowed_terms: new DataCollection(AllowedTerm::class, $terms)
    );

    $array = $config->all();
    $allowedTermsArray = array_map(fn ($term) => $term->all(), $config->allowed_terms->items());

    expect($array)
        ->toHaveKeys(['allow_installment', 'allow_full_payment', 'allowed_terms'])
        ->and($array['allow_installment'])->toBeTrue()
        ->and($array['allow_full_payment'])->toBeFalse();

    expect($allowedTermsArray)
        ->toBeArray()
        ->toHaveCount(2);

    expect($allowedTermsArray[0])
        ->toHaveKey('issuer')
        ->toHaveKey('terms')
        ->and($allowedTermsArray[0]['issuer'])->toBe('BCA');
});
