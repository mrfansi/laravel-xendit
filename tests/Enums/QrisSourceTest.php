<?php

use Mrfansi\Xendit\Enums\QrisSource;

test('qris source has correct values', function () {
    expect(QrisSource::OVO->value)->toBe('OVO')
        ->and(QrisSource::GOPAY->value)->toBe('GOPAY');
});
