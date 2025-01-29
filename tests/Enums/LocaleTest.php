<?php

use Mrfansi\Xendit\Enums\Locale;

test('locale has correct values', function () {
    expect(Locale::ENGLISH->value)->toBe('en')
        ->and(Locale::INDONESIAN->value)->toBe('id');
});

test('get default returns English', function () {
    expect(Locale::getDefault())->toBe(Locale::ENGLISH);
});

test('get display name returns correct names', function () {
    expect(Locale::ENGLISH->getDisplayName())->toBe('English')
        ->and(Locale::INDONESIAN->getDisplayName())->toBe('Indonesian');
});
