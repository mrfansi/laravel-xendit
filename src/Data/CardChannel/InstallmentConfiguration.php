<?php

namespace Mrfansi\Xendit\Data\CardChannel;

use Mrfansi\Xendit\Data\Abstracts\AbstractDataTransferObject;

/**
 * Class InstallmentConfiguration
 *
 * Represents configuration for installment payments
 */
class InstallmentConfiguration extends AbstractDataTransferObject
{
    /**
     * @param  array<AllowedTerm>|null  $allowedTerms  List of allowed installment terms
     * @param  float|null  $minAmount  Minimum amount for installment
     * @param  float|null  $maxAmount  Maximum amount for installment
     * @param  array<string>|null  $allowedIssuers  List of allowed card issuers
     */
    public function __construct(
        public ?array $allowedTerms = null,
        public ?float $minAmount = null,
        public ?float $maxAmount = null,
        public ?array $allowedIssuers = null,
    ) {}

    /**
     * Create an instance from array data
     *
     * @param  array<string, mixed>|null  $data
     */
    public static function from(?array $data): ?static
    {
        if ($data === null) {
            return null;
        }

        if (isset($data['allowed_terms']) && is_array($data['allowed_terms'])) {
            $data['allowed_terms'] = array_map(fn ($term) => AllowedTerm::fromArray($term), $data['allowed_terms']);
        }

        return static::fromArray($data);
    }

    /**
     * Create an instance from array data
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static
    {
        return new static(
            allowedTerms: $data['allowed_terms'] ?? null,
            minAmount: $data['min_amount'] ?? null,
            maxAmount: $data['max_amount'] ?? null,
            allowedIssuers: $data['allowed_issuers'] ?? null,
        );
    }
}
