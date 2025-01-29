<?php

namespace Mrfansi\Xendit\Data;

use Mrfansi\Xendit\Enums\QrisSource;
use Mrfansi\Xendit\Traits\EnumToArray;
use Spatie\LaravelData\Data;

class PaymentDetails extends Data
{
    use EnumToArray;

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

    /**
     * Convert the data to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'receipt_id' => $this->receipt_id,
            'source' => $this->enumToArray($this->source),
        ];
    }
}
