<?php

namespace Mrfansi\Xendit\Data;

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
     */
    public function __construct(
        public ?string $type = null,
        public ?float $value = null,
    ) {}
}
