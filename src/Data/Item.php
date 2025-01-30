<?php

namespace Mrfansi\Xendit\Data;

use Mrfansi\Xendit\Data\Abstracts\AbstractDataTransferObject;

/**
 * Class Item
 *
 * Represents an item in an invoice or order with its details
 */
class Item extends AbstractDataTransferObject
{
    public const MAX_NAME_LENGTH = 200;

    public const MAX_QUANTITY = 1000000;

    /**
     * Create a new Item instance
     *
     * @param  string  $name  Name of the item
     * @param  int  $quantity  Quantity of the item
     * @param  float  $price  Price of the item
     * @param  string|null  $category  Category of the item
     * @param  string|null  $url  URL of the item
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        public ?string $name,
        public ?int $quantity,
        public ?float $price,
        public ?string $category = null,
        public ?string $url = null,
    ) {
        $this->validateParams();
    }

    /**
     * Validate item parameters
     *
     * @throws InvalidArgumentException
     */
    protected function validateParams(): void
    {
        if ($this->name !== null && (strlen($this->name) < 1 || strlen($this->name) > self::MAX_NAME_LENGTH)) {
            throw new \InvalidArgumentException('name must be between 1 and '.self::MAX_NAME_LENGTH.' characters');
        }

        if ($this->quantity !== null && ($this->quantity < 1 || $this->quantity > self::MAX_QUANTITY)) {
            throw new \InvalidArgumentException('quantity must be between 1 and '.self::MAX_QUANTITY);
        }

        if ($this->url !== null && ! filter_var($this->url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('URL must be valid');
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
