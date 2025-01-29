<?php

namespace Mrfansi\Xendit\Data;

use Mrfansi\Xendit\Enums\InvoiceStatus;
use Spatie\LaravelData\Data;

class InvoiceStatusData extends Data
{
    public function __construct(
        public InvoiceStatus $status
    ) {}

    /**
     * Create a new instance of `InvoiceStatusData` from an `InvoiceStatus` enum.
     */
    public static function fromEnum(InvoiceStatus $status): static
    {
        return new self($status);
    }

    /**
     * Return the underlying `InvoiceStatus` enum.
     */
    public function toEnum(): InvoiceStatus
    {
        return $this->status;
    }
}
