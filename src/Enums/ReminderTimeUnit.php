<?php

namespace Mrfansi\XenditSdk\Enums;

enum ReminderTimeUnit: string
{
    case DAYS = 'days';
    case HOURS = 'hours';

    /**
     * Get the maximum allowed value for a given time unit
     */
    public function getMaxValue(): int
    {
        return match ($this) {
            self::DAYS => 30,
            self::HOURS => 24
        };
    }

    /**
     * Validate if the given time value is valid for this unit
     */
    public function isValidValue(int $value): bool
    {
        return $value >= 1 && $value <= $this->getMaxValue();
    }
}
