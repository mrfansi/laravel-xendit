<?php

namespace Mrfansi\Xendit\Data;

use Mrfansi\Xendit\Data\Abstracts\AbstractDataTransferObject;

/**
 * Class PaymentDetails
 *
 * Represents payment details including receipt information and payment method
 */
class PaymentDetails extends AbstractDataTransferObject
{
    /**
     * @param  string|null  $receiptId  Receipt ID of the payment
     * @param  string|null  $source  Source of the payment
     * @param  PaymentMethodData|null  $paymentMethod  Payment method used
     * @param  array<string, mixed>|null  $metadata  Additional metadata about the payment
     */
    public function __construct(
        public ?string $receiptId = null,
        public ?string $source = null,
        public ?PaymentMethodData $paymentMethod = null,
        public ?array $metadata = null,
    ) {}
}
