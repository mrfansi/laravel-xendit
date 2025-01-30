<?php

namespace Mrfansi\Xendit\Data;

use Spatie\LaravelData\AbstractDataTransferObject;

/**
 * Class Item
 *
 * Represents an item in an invoice or order with its details
 */
class Item extends AbstractDataTransferObject
{
    /**
     * @param  string|null  $name  Name of the item
     * @param  int|null  $quantity  Quantity of the item
     * @param  float|null  $price  Price per unit of the item
     * @param  string|null  $referenceId  Reference ID for the item in your system
     * @param  string|null  $url  URL associated with the item (e.g., product page)
     * @param  string|null  $category  Category of the item
     * @param  string|null  $description  Additional description of the item
     */
    public function __construct(
        public ?string $name = null,
        public ?int $quantity = null,
        public ?float $price = null,
        public ?string $referenceId = null,
        public ?string $url = null,
        public ?string $category = null,
        public ?string $description = null,
    ) {}
}
