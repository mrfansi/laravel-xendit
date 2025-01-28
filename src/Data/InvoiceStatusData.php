<?php

namespace Mrfansi\XenditSdk\Data;

use Mrfansi\XenditSdk\Enums\InvoiceStatus;
use Spatie\LaravelData\Data;

class InvoiceStatusData extends Data
{
    public function __construct(
        public InvoiceStatus $status
    ) {}

    public static function fromEnum(InvoiceStatus $status): self
    {
        return new self($status);
    }

    public function toEnum(): InvoiceStatus
    {
        return $this->status;
    }
}
