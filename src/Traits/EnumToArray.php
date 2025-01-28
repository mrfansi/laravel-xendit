<?php

namespace Mrfansi\XenditSdk\Traits;

trait EnumToArray
{
    /**
     * Convert enum value to string when converting to array
     */
    public function enumToArray($value): ?string
    {
        if ($value === null) {
            return null;
        }

        return $value->value;
    }
}
