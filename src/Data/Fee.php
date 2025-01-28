<?php

namespace Mrfansi\XenditSdk\Data;

use Spatie\LaravelData\Data;

class Fee extends Data
{
    public function __construct(
        /**
         * Type of fee (e.g., admin, shipping, tax, discount)
         */
        public string $type,

        /**
         * Amount of the fee (can be positive or negative)
         */
        public float $value,
    ) {
        // Basic validation to ensure type is not empty
        if (empty(trim($type))) {
            throw new \InvalidArgumentException('Fee type cannot be empty');
        }
    }
}
