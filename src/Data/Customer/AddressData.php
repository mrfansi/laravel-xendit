<?php

namespace Mrfansi\LaravelXendit\Data\Customer;

use Mrfansi\LaravelXendit\Enums\AddressCategory;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;
use Mrfansi\LaravelXendit\Traits\HasAddressValidationRules;
use Mrfansi\LaravelXendit\Traits\HasCountry;

/**
 * Represents a customer's address information.
 */
class AddressData
{
    use HasAddressValidationRules;
    use HasCountry;

    public function __construct(
        public string $country,
        public ?string $provinceState = null,
        public ?string $city = null,
        public ?string $streetLine1 = null,
        public ?string $streetLine2 = null,
        public ?string $postalCode = null,
        public AddressCategory|string|null $category = null,
        public ?bool $isPrimary = false,
    ) {
        $country = $this->getCountryCode($country);

        $this->validateCountry($country);
        $this->validateProvinceState($provinceState);
        $this->validateCity($city);
        $this->validateStreetLine1($streetLine1);
        $this->validateStreetLine2($streetLine2);
        $this->validatePostalCode($postalCode);
        $this->validateCategory($category);
    }

    /**
     * Sets the country for this address.
     *
     * @param  string  $country  ISO 3166-2 country code (two letters)
     * @return $this
     *
     * @throws ValidationException
     */
    public function setCountry(string $country): self
    {
        $this->validateCountry($country);
        $this->country = strtoupper($country);

        return $this;
    }

    /**
     * Sets the province or state for this address.
     *
     * @param  string|null  $provinceState  Maximum 255 characters, alphanumeric only
     * @return $this
     *
     * @throws ValidationException
     */
    public function setProvinceState(?string $provinceState): self
    {
        $this->validateProvinceState($provinceState);
        $this->provinceState = $provinceState;

        return $this;
    }

    /**
     * Sets the city for this address.
     *
     * @param  string|null  $city  Maximum 255 characters, alphanumeric only
     * @return $this
     *
     * @throws ValidationException
     */
    public function setCity(?string $city): self
    {
        $this->validateCity($city);
        $this->city = $city;

        return $this;
    }

    /**
     * Sets the first line of the street address.
     *
     * @param  string|null  $streetLine1  Maximum 255 characters, alphanumeric only
     * @return $this
     *
     * @throws ValidationException
     */
    public function setStreetLine1(?string $streetLine1): self
    {
        $this->validateStreetLine1($streetLine1);
        $this->streetLine1 = $streetLine1;

        return $this;
    }

    /**
     * Sets the second line of the street address.
     *
     * @param  string|null  $streetLine2  Maximum 255 characters, alphanumeric only
     * @return $this
     *
     * @throws ValidationException
     */
    public function setStreetLine2(?string $streetLine2): self
    {
        $this->validateStreetLine2($streetLine2);
        $this->streetLine2 = $streetLine2;

        return $this;
    }

    /**
     * Sets the postal code for this address.
     *
     * @param  string|null  $postalCode  Maximum 255 characters, alphanumeric only
     * @return $this
     *
     * @throws ValidationException
     */
    public function setPostalCode(?string $postalCode): self
    {
        $this->validatePostalCode($postalCode);
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Sets the category for this address.
     *
     * @param  AddressCategory|string|null  $category  Must be one of: HOME, WORK, PROVINCIAL
     * @return $this
     *
     * @throws ValidationException
     */
    public function setCategory(AddressCategory|string|null $category): self
    {
        $this->validateCategory($category?->value ?? $category);
        $this->category = $category;

        return $this;
    }

    /**
     * Sets whether this address is the primary address for the customer.
     *
     * @param  bool|null  $isPrimary  Defaults to false
     * @return $this
     */
    public function setIsPrimary(?bool $isPrimary): self
    {
        $this->isPrimary = $isPrimary;

        return $this;
    }

    /**
     * Converts the address data to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $array = ['country' => $this->country];

        if ($this->provinceState !== null) {
            $array['province_state'] = $this->provinceState;
        }

        if ($this->city !== null) {
            $array['city'] = $this->city;
        }

        if ($this->streetLine1 !== null) {
            $array['street_line1'] = $this->streetLine1;
        }

        if ($this->streetLine2 !== null) {
            $array['street_line2'] = $this->streetLine2;
        }

        if ($this->postalCode !== null) {
            $array['postal_code'] = $this->postalCode;
        }

        if ($this->category !== null) {
            $array['category'] = $this->category instanceof AddressCategory ? $this->category->value : $this->category;
        }

        if ($this->isPrimary) {
            $array['is_primary'] = $this->isPrimary;
        }

        return $array;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            country: $data['country'],
            provinceState: $data['province_state'] ?? null,
            city: $data['city'] ?? null,
            streetLine1: $data['street_line1'] ?? null,
            streetLine2: $data['street_line2'] ?? null,
            postalCode: $data['postal_code'] ?? null,
            category: isset($data['category']) ? AddressCategory::from($data['category']) : null,
            isPrimary: $data['is_primary'] ?? false,
        );
    }
}
