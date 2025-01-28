<?php

namespace Mrfansi\XenditSdk\Data;

use Mrfansi\XenditSdk\Enums\QrisSource;
use Spatie\LaravelData\Data;

class PaymentDetails extends Data
{
    public function __construct(
        /**
         * Request reference number (RRN) shared across QR network
         */
        public ?string $receipt_id = null,

        /**
         * Source where end user's balance was deducted
         */
        public ?QrisSource $source = null,
    ) {}

    public function toArray(): array
    {
        return [
            'receipt_id' => $this->receipt_id,
            'source' => $this->source?->value,
        ];
    }
}
