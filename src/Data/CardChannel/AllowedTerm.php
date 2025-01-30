<?php

namespace Mrfansi\Xendit\Data\CardChannel;

use Mrfansi\Xendit\Data\Abstracts\AbstractDataTransferObject;

/**
 * Class AllowedTerm
 *
 * Represents allowed term configuration for card payments
 */
class AllowedTerm extends AbstractDataTransferObject
{
    /**
     * List of allowed issuers for card payments
     */
    public const ALLOWED_ISSUERS = [
        'BCA',
        'BNI',
        'BRI',
        'MANDIRI',
        'CIMB',
        'MEGA',
        'PERMATA'
    ];

    /**
     * @param  string|null  $issuer  Bank issuer
     * @param  array|null  $terms  List of allowed terms
     * @param  float|null  $minAmount  Minimum amount for this term
     * @param  float|null  $maxAmount  Maximum amount for this term
     * @throws \InvalidArgumentException
     */
    public function __construct(
        public ?string $issuer = null,
        public ?array $terms = null,
        public ?float $minAmount = null,
        public ?float $maxAmount = null,
    ) {
        if ($issuer !== null && !in_array($issuer, self::ALLOWED_ISSUERS)) {
            throw new \InvalidArgumentException('Invalid issuer');
        }

        if ($terms !== null) {
            foreach ($terms as $term) {
                if (!is_int($term) || $term <= 0) {
                    throw new \InvalidArgumentException('Terms must be positive integers');
                }
            }
        }
    }

    /**
     * Convert the object to an array.
     */
    public function toArray(): array
    {
        return array_filter([
            'issuer' => $this->issuer,
            'terms' => $this->terms,
            'min_amount' => $this->minAmount,
            'max_amount' => $this->maxAmount,
        ], fn ($value) => $value !== null);
    }
}
