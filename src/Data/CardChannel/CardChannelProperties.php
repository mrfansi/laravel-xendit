<?php

namespace Mrfansi\Xendit\Data\CardChannel;

use Mrfansi\Xendit\Data\Abstracts\AbstractDataTransferObject;

/**
 * Class CardChannelProperties
 *
 * Represents properties specific to card payment channel
 */
class CardChannelProperties extends AbstractDataTransferObject
{
    /**
     * @param  string|null  $skipThreeDSecure  Whether to skip 3D Secure authentication
     * @param  array<AllowedTerm>|null  $allowedTerms  List of allowed installment terms
     * @param  InstallmentConfiguration|null  $installmentConfiguration  Configuration for installment payments
     */
    public function __construct(
        public ?string $skipThreeDSecure = null,
        public ?array $allowedTerms = null,
        public ?InstallmentConfiguration $installmentConfiguration = null,
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

        if (isset($data['installment_configuration'])) {
            $data['installment_configuration'] = InstallmentConfiguration::fromArray($data['installment_configuration']);
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
            skipThreeDSecure: $data['skip_three_d_secure'] ?? null,
            allowedTerms: $data['allowed_terms'] ?? null,
            installmentConfiguration: $data['installment_configuration'] ?? null,
        );
    }
}
