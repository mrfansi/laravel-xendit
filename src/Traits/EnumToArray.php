<?php

namespace Mrfansi\LaravelXendit\Traits;

trait EnumToArray
{
    /**
     * Convert enum value to string when converting to array
     */
    public function enumToArray($value): ?string
    {
        return $value?->value;
    }
}
