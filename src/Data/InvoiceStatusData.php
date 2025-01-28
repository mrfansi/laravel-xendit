<?php

namespace Mrfansi\XenditSdk\Data;

use Mrfansi\XenditSdk\Enums\InvoiceStatus;
use Spatie\LaravelData\Data;

class InvoiceStatusData extends Data
{
    public function __construct(
        public InvoiceStatus $status
    ) {}

    /**
     * Create a new instance of `InvoiceStatusData` from an `InvoiceStatus` enum.
     *
     * @return static
     */
    public static function fromEnum(InvoiceStatus $status): self
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
