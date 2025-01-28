<?php

namespace Mrfansi\XenditSdk\Data;

use Spatie\LaravelData\Data;

class Item extends Data
{
    /**
     * Item constructor.
     *
     * @param  string  $name  Item name. The name of the item being purchased.
     * @param  int  $quantity  Item quantity. The quantity of the item being purchased.
     * @param  float  $price  Item price. The price of the item being purchased.
     * @param  string|null  $category  Item category. The category of the item being purchased.
     * @param  string|null  $url  Item URL. The URL to the item being purchased.
     *
     * @throws \InvalidArgumentException If any of the validation rules fail.
     */
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
