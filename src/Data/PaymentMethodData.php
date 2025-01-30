<?php

namespace Mrfansi\Xendit\Data;

use Mrfansi\Xendit\Data\Abstracts\AbstractDataTransferObject;

/**
 * Class PaymentMethodData
 * 
 * Represents payment method data with type and reusability information
 */
class PaymentMethodData extends AbstractDataTransferObject
{
    /**
     * @param string|null $type Payment method type (e.g., 'CARD', 'VIRTUAL_ACCOUNT', etc.)
     * @param bool|null $reusability Whether the payment method can be reused
     */
    public function __construct(
        public ?string $type = null,
        public ?bool $reusability = null,
    ) {
    }
}
