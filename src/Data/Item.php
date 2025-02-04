<?php

namespace Mrfansi\LaravelXendit\Data;

use Mrfansi\LaravelXendit\Data\Abstracts\AbstractDataTransferObject;

/**
 * Class Item
 *
 * Represents an item in an invoice or order with its details
 */
class Item extends AbstractDataTransferObject
{
    public const MAX_NAME_LENGTH = 256;

    public const MAX_QUANTITY = 510000;

    /**
     * Create a new Item instance
     *
     * @param string|null $name Name of the item
     * @param int|null $quantity Quantity of the item
     * @param float|null $price Price of the item
     * @param string|null $category Category of the item
     * @param string|null $url URL of the item
     *
     */
    public function __construct(
        public ?string $name,
        public ?int $quantity,
        public ?float $price,
        public ?string $category = null,
        public ?string $url = null,
    ) {
        if ($name !== null && strlen($name) > self::MAX_NAME_LENGTH) {
            throw new \InvalidArgumentException('Item name cannot exceed '.self::MAX_NAME_LENGTH.' characters');
        }

        if ($quantity !== null && $quantity > self::MAX_QUANTITY) {
            throw new \InvalidArgumentException('Item quantity cannot exceed '.self::MAX_QUANTITY);
        }

        if ($url !== null && ! filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
            throw new \InvalidArgumentException('Item URL must be a valid HTTP or HTTPS URL');
        }
    }

    /**
     * Convert instance to array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'category' => $this->category,
            'url' => $this->url,
        ];
    }
}
