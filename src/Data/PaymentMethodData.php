<?php

namespace Mrfansi\Xendit\Data;

use Mrfansi\Xendit\Data\Abstracts\AbstractDataTransferObject;
use Mrfansi\Xendit\Enums\PaymentMethod;
use ReflectionClass;

/**
 * Class PaymentMethodData
 *
 * Represents payment method data with type and reusability information
 */
class PaymentMethodData extends AbstractDataTransferObject
{
    /**
     * @param  string|null  $type  Payment method type (e.g., 'CARD', 'VIRTUAL_ACCOUNT', etc.)
     * @param  bool|null  $reusability  Whether the payment method can be reused
     */
    public function __construct(
        public ?string $type = null,
        public ?bool $reusability = null,
    ) {}

    /**
     * Create an instance from PaymentMethod enum
     *
     * @param  PaymentMethod  $method
     */
    public static function fromEnum(PaymentMethod $method): static
    {
        return new static(type: $method->value);
    }

    /**
     * Create an instance from array data
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        $instance = (new ReflectionClass(static::class))->newInstance();
        $instance->type = $data['type'] ?? null;
        $instance->reusability = $data['reusability'] ?? null;
        return $instance;
    }
}
