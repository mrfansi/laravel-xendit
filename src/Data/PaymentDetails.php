<?php

namespace Mrfansi\Xendit\Data;

use Mrfansi\Xendit\Enums\QrisSource;
use Mrfansi\Xendit\Data\Abstracts\AbstractDataTransferObject;
use ReflectionClass;

/**
 * Class PaymentDetails
 *
 * Represents payment details including receipt information and payment method
 */
class PaymentDetails extends AbstractDataTransferObject
{
    /**
     * @param  string|null  $receipt_id  Receipt ID of the payment
     * @param  QrisSource|null  $source  Source of the payment
     * @param  PaymentMethodData|null  $payment_method  Payment method used
     * @param  array<string, mixed>|null  $metadata  Additional metadata about the payment
     */
    public function __construct(
        public ?string $receipt_id = null,
        public ?QrisSource $source = null,
        public ?PaymentMethodData $payment_method = null,
        public ?array $metadata = null,
    ) {}

    /**
     * Create an instance from array data
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        $instance = (new ReflectionClass(static::class))->newInstance();

        $instance->receipt_id = $data['receipt_id'] ?? null;
        $instance->source = isset($data['source']) ? QrisSource::from($data['source']) : null;
        $instance->payment_method = isset($data['payment_method']) ? PaymentMethodData::fromArray($data['payment_method']) : null;
        $instance->metadata = $data['metadata'] ?? null;

        return $instance;
    }
}
