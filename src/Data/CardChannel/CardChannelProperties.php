<?php

namespace Mrfansi\Xendit\Data\CardChannel;

use Mrfansi\Xendit\Data\Abstracts\AbstractDataTransferObject;
use ReflectionClass;

/**
 * Class CardChannelProperties
 *
 * Represents properties specific to card payment channel
 */
class CardChannelProperties extends AbstractDataTransferObject
{
    /**
     * @param  array<string>|null  $allowed_bins  List of allowed BIN numbers
     * @param  string|null  $skip_three_d_secure  Whether to skip 3D Secure authentication
     * @param  array<AllowedTerm>|null  $allowed_terms  List of allowed installment terms
     * @param  InstallmentConfiguration|null  $installment_configuration  Configuration for installment payments
     * @throws \InvalidArgumentException
     */
    public function __construct(
        public ?array $allowed_bins = null,
        public ?string $skip_three_d_secure = null,
        public ?array $allowed_terms = null,
        public ?InstallmentConfiguration $installment_configuration = null,
    ) {
        if ($allowed_bins !== null) {
            foreach ($allowed_bins as $bin) {
                if (!preg_match('/^\d{6}$|^\d{8}$/', $bin)) {
                    throw new \InvalidArgumentException('Credit card BIN must be either 6 or 8 digits');
                }
            }
        }
    }

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
        /** @var static */
        $instance = (new ReflectionClass(static::class))->newInstance();

        $instance->allowed_bins = $data['allowed_bins'] ?? null;
        $instance->skip_three_d_secure = $data['skip_three_d_secure'] ?? null;
        $instance->allowed_terms = $data['allowed_terms'] ?? null;
        $instance->installment_configuration = $data['installment_configuration'] ?? null;

        return $instance;
    }
}
