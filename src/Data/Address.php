<?php

namespace Mrfansi\Xendit\Data;

use Spatie\LaravelData\Data;

class Address extends Data
{
    /**
     * Constructs a new Address object.
     *
     * @param  string|null  $city  City where the address is located
     * @param  string|null  $country  Country where the address is located (ISO 3166-1 alpha-2)
     * @param  string|null  $postal_code  Postal code of the address
     * @param  string|null  $state  State/province/region where the address is located
     * @param  string|null  $street_line1  Street address line 1
     * @param  string|null  $street_line2  Street address line 2
     */
    public function __construct(
        public ?string $city,
        public ?string $country,
        public ?string $postal_code,
        public ?string $state,
        public ?string $street_line1,
        public ?string $street_line2,
    ) {}

    /**
     * Creates an Address instance from an array.
     *
     * @param  array<string, mixed>  $data  The array containing address data
     */
    public static function fromArray(array $data): static
    {
        return new static(
            city: $data['city'] ?? null,
            country: $data['country'] ?? null,
            postal_code: $data['postal_code'] ?? null,
            state: $data['state'] ?? null,
            street_line1: $data['street_line1'] ?? null,
            street_line2: $data['street_line2'] ?? null,
        );
    }

    /**
     * Converts the Address instance to an array.
     *
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return [
            'city' => $this->city,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'state' => $this->state,
            'street_line1' => $this->street_line1,
            'street_line2' => $this->street_line2,
        ];
    }
}
