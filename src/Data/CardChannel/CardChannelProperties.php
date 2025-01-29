<?php

namespace Mrfansi\Xendit\Data\CardChannel;

use Spatie\LaravelData\Data;

class CardChannelProperties extends Data
{
    /**
     * Constructor for CardChannelProperties.
     *
     * @param  string[]|null  $allowed_bins  Credit card BINs that will be accepted (6 or 8 digits)
     * @param  InstallmentConfiguration|null  $installment_configuration  Installment configuration
     */
    public function __construct(
        /**
         * Credit card BINs that will be accepted (6 or 8 digits)
         *
         * @var string[]|null
         */
        public ?array $allowed_bins = null,

        /**
         * Installment configuration
         */
        public ?InstallmentConfiguration $installment_configuration = null,
    ) {
        // Validate BIN lengths if provided
        if ($this->allowed_bins !== null) {
            foreach ($this->allowed_bins as $bin) {
                if (! preg_match('/^\d{6}(\d{2})?$/', $bin)) {
                    throw new \InvalidArgumentException(
                        'Credit card BIN must be either 6 or 8 digits'
                    );
                }
            }
        }
    }
}
