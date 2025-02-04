<?php

namespace Mrfansi\LaravelXendit\Data\CardChannel;

use Mrfansi\LaravelXendit\Data\Abstracts\AbstractDataTransferObject;
use ReflectionClass;

/**
 * Class InstallmentConfiguration
 *
 * Represents configuration for installment payments
 */
class InstallmentConfiguration extends AbstractDataTransferObject
{
    /**
     * @param  bool|null  $allow_installment  Whether to allow installment payments
     * @param  bool|null  $allow_full_payment  Whether to allow full payment
     * @param  array<AllowedTerm>|null  $allowed_terms  List of allowed installment terms
     * @param  float|null  $min_amount  Minimum amount for installment
     * @param  float|null  $max_amount  Maximum amount for installment
     * @param  array<string>|null  $allowed_issuers  List of allowed card issuers
     */
    public function __construct(
        public ?bool $allow_installment = true,
        public ?bool $allow_full_payment = true,
        public ?array $allowed_terms = null,
        public ?float $min_amount = null,
        public ?float $max_amount = null,
        public ?array $allowed_issuers = null,
    ) {}

    /**
     * Create an instance from array data
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        $instance = (new ReflectionClass(static::class))->newInstance();

        $instance->allow_installment = $data['allow_installment'] ?? true;
        $instance->allow_full_payment = $data['allow_full_payment'] ?? true;
        $instance->allowed_terms = isset($data['allowed_terms']) ? array_map(fn ($term) => AllowedTerm::fromArray($term), $data['allowed_terms']) : null;
        $instance->min_amount = $data['min_amount'] ?? null;
        $instance->max_amount = $data['max_amount'] ?? null;
        $instance->allowed_issuers = $data['allowed_issuers'] ?? null;

        return $instance;
    }
}
