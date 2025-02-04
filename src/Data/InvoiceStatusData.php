<?php

namespace Mrfansi\LaravelXendit\Data;

use Mrfansi\LaravelXendit\Data\Abstracts\AbstractDataTransferObject;
use Mrfansi\LaravelXendit\Enums\InvoiceStatus;
use ReflectionClass;

/**
 * Class InvoiceStatusData
 *
 * Represents the status of an invoice
 */
class InvoiceStatusData extends AbstractDataTransferObject
{
    /**
     * @param  string|null  $status  Current status of the invoice (e.g., 'PENDING', 'PAID', 'EXPIRED')
     */
    public function __construct(
        public ?string $status = null,
    ) {}

    /**
     * Create an instance from InvoiceStatus enum
     */
    public static function fromEnum(InvoiceStatus $status): static
    {
        return new static(status: $status->value);
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
        $instance->status = $data['status'] ?? null;

        return $instance;
    }
}
