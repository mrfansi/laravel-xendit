<?php

namespace Mrfansi\LaravelXendit\Data;

class InvoiceData
{
    public function __construct(
        public string $externalId,
        public int $amount,
        public ?string $description = null,
    ) {}
}
