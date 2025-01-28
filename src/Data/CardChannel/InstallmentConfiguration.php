<?php

namespace Mrfansi\XenditSdk\Data\CardChannel;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class InstallmentConfiguration extends Data
{
    public function __construct(
        /**
         * Whether end users can pay with installments
         */
        public ?bool $allow_installment = true,

        /**
         * Whether to allow full payment option
         */
        public ?bool $allow_full_payment = true,

        /**
         * Allowed terms configuration for different issuers
         */
        #[DataCollectionOf(AllowedTerm::class)]
        public ?DataCollection $allowed_terms = null
    ) {}
}
