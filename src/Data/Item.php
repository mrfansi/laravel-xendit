<?php

namespace Mrfansi\XenditSdk\Data;

use Spatie\LaravelData\Data;

class Item extends Data
{
    public function __construct(
        public string $name,
        public int $quantity,
        public float $price,
        public ?string $category = null,
        public ?string $url = null,
    ) {
        // Validation rules
        if (strlen($name) > 256) {
            throw new \InvalidArgumentException('Item name cannot exceed 256 characters');
        }

        if ($quantity > 510000) {
            throw new \InvalidArgumentException('Item quantity cannot exceed 510000');
        }

        if ($url !== null && ! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Item URL must be a valid HTTP or HTTPS URL');
        }
    }
}
