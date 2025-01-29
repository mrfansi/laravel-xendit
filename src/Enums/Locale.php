<?php

namespace Mrfansi\Xendit\Enums;

enum Locale: string
{
    case ENGLISH = 'en';
    case INDONESIAN = 'id';

    /**
     * Get the default locale
     */
    public static function getDefault(): self
    {
        return self::ENGLISH;
    }

    /**
     * Get human-readable name for the locale
     */
    public function getDisplayName(): string
    {
        return match ($this) {
            self::ENGLISH => 'English',
            self::INDONESIAN => 'Indonesian'
        };
    }
}
