<?php

namespace Mrfansi\Xendit\Data\CardChannel;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class InstallmentConfiguration extends Data
{
    /**
     * Creates a new InstallmentConfiguration instance
     *
     * @param  bool  $allow_installment  Whether end users can pay with installments
     * @param  bool  $allow_full_payment  Whether to allow full payment option
     * @param  DataCollection<AllowedTerm>|null  $allowed_terms  Allowed terms configuration for different issuers
     */
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
