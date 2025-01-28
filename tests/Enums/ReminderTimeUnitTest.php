<?php

use Mrfansi\XenditSdk\Enums\ReminderTimeUnit;

test('reminder time unit has correct values', function () {
    expect(ReminderTimeUnit::DAYS->value)->toBe('days')
        ->and(ReminderTimeUnit::HOURS->value)->toBe('hours');
});

test('get max value returns correct limits', function () {
    expect(ReminderTimeUnit::DAYS->getMaxValue())->toBe(30)
        ->and(ReminderTimeUnit::HOURS->getMaxValue())->toBe(24);
});

test('validates time values correctly', function () {
    // Valid cases
    expect(ReminderTimeUnit::DAYS->isValidValue(1))->toBeTrue()
        ->and(ReminderTimeUnit::DAYS->isValidValue(15))->toBeTrue()
        ->and(ReminderTimeUnit::DAYS->isValidValue(30))->toBeTrue()
        ->and(ReminderTimeUnit::HOURS->isValidValue(1))->toBeTrue()
        ->and(ReminderTimeUnit::HOURS->isValidValue(12))->toBeTrue()
        ->and(ReminderTimeUnit::HOURS->isValidValue(24))->toBeTrue();

    // Invalid cases
    expect(ReminderTimeUnit::DAYS->isValidValue(0))->toBeFalse()
        ->and(ReminderTimeUnit::DAYS->isValidValue(31))->toBeFalse()
        ->and(ReminderTimeUnit::DAYS->isValidValue(-1))->toBeFalse()
        ->and(ReminderTimeUnit::HOURS->isValidValue(0))->toBeFalse()
        ->and(ReminderTimeUnit::HOURS->isValidValue(25))->toBeFalse()
        ->and(ReminderTimeUnit::HOURS->isValidValue(-1))->toBeFalse();
});
