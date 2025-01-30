<?php

namespace Mrfansi\Xendit\Data;

use Mrfansi\Xendit\Data\Abstracts\AbstractDataTransferObject;

/**
 * Class Fee
 *
 * Represents a fee with type and amount information
 */
class Fee extends AbstractDataTransferObject
{
    /**
     * @param  string|null  $type  Type of the fee (e.g., 'ADMIN', 'SERVICE', etc.)
     * @param  float|null  $value  Amount of the fee
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        public ?string $type = null,
        public ?float $value = null,
    ) {
        if ($type !== null && trim($type) === '') {
            throw new \InvalidArgumentException('Fee type cannot be empty');
        }
    }
}
