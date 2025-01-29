<?php

use Mrfansi\Xendit\Data\CardChannel\AllowedTerm;

test('allowed term can be created with valid issuer and terms', function () {
    $term = new AllowedTerm(
        issuer: 'BCA',
        terms: [3, 6, 12]
    );

    expect($term)
        ->issuer->toBe('BCA')
        ->terms->toBe([3, 6, 12]);
});

test('allowed term validates issuer is in allowed list', function () {
    expect(fn () => new AllowedTerm(
        issuer: 'INVALID_BANK',
        terms: [3, 6]
    ))->toThrow(InvalidArgumentException::class, 'Invalid issuer');
});

test('all predefined issuers are valid', function () {
    foreach (AllowedTerm::ALLOWED_ISSUERS as $issuer) {
        expect(fn () => new AllowedTerm(
            issuer: $issuer,
            terms: [3]
        ))->not->toThrow(InvalidArgumentException::class);
    }
});

test('allowed term validates terms are positive integers', function () {
    // Test with zero
    expect(fn () => new AllowedTerm(
        issuer: 'BCA',
        terms: [0]
    ))->toThrow(InvalidArgumentException::class, 'Terms must be positive integers');

    // Test with negative number
    expect(fn () => new AllowedTerm(
        issuer: 'BCA',
        terms: [-3]
    ))->toThrow(InvalidArgumentException::class, 'Terms must be positive integers');

    // Test with float
    expect(fn () => new AllowedTerm(
        issuer: 'BCA',
        terms: [3.5]
    ))->toThrow(InvalidArgumentException::class, 'Terms must be positive integers');
});

test('allowed term can be converted to array', function () {
    $term = new AllowedTerm(
        issuer: 'BCA',
        terms: [3, 6, 12]
    );

    $array = $term->all();

    expect($array)
        ->toHaveKeys(['issuer', 'terms'])
        ->and($array['issuer'])->toBe('BCA')
        ->and($array['terms'])->toBe([3, 6, 12]);
});

test('allowed term can handle multiple terms', function () {
    $term = new AllowedTerm(
        issuer: 'MANDIRI',
        terms: [3, 6, 9, 12, 18, 24]
    );

    expect($term->terms)
        ->toBeArray()
        ->toHaveCount(6)
        ->toBe([3, 6, 9, 12, 18, 24]);
});
