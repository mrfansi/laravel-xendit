<?php

namespace Mrfansi\Xendit\Data;

use Spatie\LaravelData\Data;

class Fee extends Data
{
    /**
     * Creates a new instance of the Fee class.
     *
     * @param  string  $type  Type of fee (e.g., admin, shipping, tax, discount)
     * @param  float  $value  Amount of the fee (can be positive or negative)
     */
    public function __construct(
        public string $type,
        public float $value,
    ) {
        // Basic validation to ensure type is not empty
        if (empty(trim($type))) {
            throw new \InvalidArgumentException('Fee type cannot be empty');
        }
    }
}
