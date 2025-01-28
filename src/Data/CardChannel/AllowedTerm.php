<?php

namespace Mrfansi\XenditSdk\Data\CardChannel;

use Spatie\LaravelData\Data;

class AllowedTerm extends Data
{
    public const ALLOWED_ISSUERS = [
        'BCA', 'BNI', 'MANDIRI', 'BRI', 'PERMATA', 'OCBCNISP',
        'HSBCID', 'BTPN', 'DBSID', 'CIMB', 'DANAMON', 'UOBID',
        'MAYBANKID', 'BSI',
    ];

    /**
     * Constructor for AllowedTerm
     *
     * @param  string  $issuer  Issuing bank of the credit card
     * @param  int[]  $terms  Terms of installment payment (positive numbers)
     */
    public function __construct(
        /**
         * Issuing bank of the credit card
         */
        public string $issuer,

        /**
         * Terms of installment payment (positive numbers)
         *
         * @var int[]
         */
        public array $terms,
    ) {
        // Validate issuer is in allowed list
        if (! in_array($issuer, self::ALLOWED_ISSUERS)) {
            throw new \InvalidArgumentException(
                'Invalid issuer. Must be one of: '.implode(', ', self::ALLOWED_ISSUERS)
            );
        }

        // Validate terms are positive numbers
        foreach ($terms as $term) {
            if (! is_int($term) || $term <= 0) {
                throw new \InvalidArgumentException('Terms must be positive integers');
            }
        }
    }
}
