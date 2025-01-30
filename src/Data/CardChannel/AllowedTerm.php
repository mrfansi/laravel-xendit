<?php

namespace Mrfansi\Xendit\Data\CardChannel;

use Mrfansi\Xendit\Data\AbstractDataTransferObject;

/**
 * Class AllowedTerm
 *
 * Represents allowed term configuration for card payments
 */
class AllowedTerm extends AbstractDataTransferObject
{
    /**
     * @param  int|null  $term  Term length
     * @param  float|null  $minAmount  Minimum amount for this term
     * @param  float|null  $maxAmount  Maximum amount for this term
     */
    public function __construct(
        public ?int $term = null,
        public ?float $minAmount = null,
        public ?float $maxAmount = null,
    ) {}
}
