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
    public const MAX_NAME_LENGTH = 256;
    public const MAX_QUANTITY = 510000;

    /**
     * @param  string|null  $name  Name of the item
     * @param  int|null  $quantity  Quantity of the item
     * @param  float|null  $price  Price per unit of the item
     * @param  string|null  $referenceId  Reference ID for the item in your system
     * @param  string|null  $url  URL associated with the item (e.g., product page)
     * @param  string|null  $category  Category of the item
     * @param  string|null  $description  Additional description of the item
     * @throws \InvalidArgumentException
     */
    public function __construct(
        public ?string $name = null,
        public ?int $quantity = null,
        public ?float $price = null,
        public ?string $referenceId = null,
        public ?string $url = null,
        public ?string $category = null,
        public ?string $description = null,
    ) {
        if ($name !== null && strlen($name) > self::MAX_NAME_LENGTH) {
            throw new \InvalidArgumentException('Item name cannot exceed ' . self::MAX_NAME_LENGTH . ' characters');
        }

        if ($quantity !== null && $quantity > self::MAX_QUANTITY) {
            throw new \InvalidArgumentException('Item quantity cannot exceed ' . self::MAX_QUANTITY);
        }

        if ($url !== null && !filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
            throw new \InvalidArgumentException('Item URL must be a valid HTTP or HTTPS URL');
        }
    }
}
