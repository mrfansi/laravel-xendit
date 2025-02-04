<?php

namespace Mrfansi\LaravelXendit\Data\Customer;

use Mrfansi\LaravelXendit\Enums\AddressCategory;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;

/**
 * Represents a customer's address information.
 */
class AddressData
{
    /**
     * Maximum length for address fields.
     */
    private const MAX_LENGTH = 255;

    public function __construct(
        public string $country,
        public ?string $provinceState = null,
        public ?string $city = null,
        public ?string $streetLine1 = null,
        public ?string $streetLine2 = null,
        public ?string $postalCode = null,
        public ?string $category = null,
        public ?bool $isPrimary = false,
    ) {
        $this->validateCountry($country);
        $this->validateProvinceState($provinceState);
        $this->validateCity($city);
        $this->validateStreetLine1($streetLine1);
        $this->validateStreetLine2($streetLine2);
        $this->validatePostalCode($postalCode);
        $this->validateCategory($category);
    }

    /**
     * Validates if a string is alphanumeric (including spaces).
     */
    private function isAlphanumeric(?string $value): bool
    {
        if ($value === null) {
            return true;
        }

        // Allow common address characters: letters, numbers, spaces, periods, commas, dashes, and forward slashes
        return preg_match('/^[a-zA-Z0-9\s.,\-\/]+$/', $value) === 1;
    }

    /**
     * Validates the ISO 3166-2 country code.
     *
     * @throws ValidationException
     */
    private function validateCountry(string $country): void
    {
        if (strlen($country) !== 2 || ! ctype_alpha($country)) {
            throw new ValidationException('Country must be a valid ISO 3166-2 code (2 letters)');
        }
    }

    /**
     * Validates the province/state field.
     *
     * @throws ValidationException
     */
    private function validateProvinceState(?string $value): void
    {
        if ($value !== null) {
            if (strlen($value) > self::MAX_LENGTH) {
                throw new ValidationException('Province/state must not exceed 255 characters');
            }
            if (! $this->isAlphanumeric($value)) {
                throw new ValidationException('Province/state must be alphanumeric');
            }
        }
    }

    /**
     * Validates the city field.
     *
     * @throws ValidationException
     */
    private function validateCity(?string $value): void
    {
        if ($value !== null) {
            if (strlen($value) > self::MAX_LENGTH) {
                throw new ValidationException('City must not exceed 255 characters');
            }
            if (! $this->isAlphanumeric($value)) {
                throw new ValidationException('City must be alphanumeric');
            }
        }
    }

    /**
     * Validates the street line 1 field.
     *
     * @throws ValidationException
     */
    private function validateStreetLine1(?string $value): void
    {
        if ($value !== null) {
            if (strlen($value) > self::MAX_LENGTH) {
                throw new ValidationException('Street line 1 must not exceed 255 characters');
            }
            if (! $this->isAlphanumeric($value)) {
                throw new ValidationException('Street line 1 must be alphanumeric');
            }
        }
    }

    /**
     * Validates the street line 2 field.
     *
     * @throws ValidationException
     */
    private function validateStreetLine2(?string $value): void
    {
        if ($value !== null) {
            if (strlen($value) > self::MAX_LENGTH) {
                throw new ValidationException('Street line 2 must not exceed 255 characters');
            }
            if (! $this->isAlphanumeric($value)) {
                throw new ValidationException('Street line 2 must be alphanumeric');
            }
        }
    }

    /**
     * Validates the postal code field.
     *
     * @throws ValidationException
     */
    private function validatePostalCode(?string $value): void
    {
        if ($value !== null) {
            if (strlen($value) > self::MAX_LENGTH) {
                throw new ValidationException('Postal code must not exceed 255 characters');
            }
            if (! $this->isAlphanumeric($value)) {
                throw new ValidationException('Postal code must be alphanumeric');
            }
        }
    }

    /**
     * Validates the category field.
     *
     * @throws ValidationException
     */
    private function validateCategory(?string $value): void
    {
        if ($value !== null) {
            try {
                AddressCategory::from($value);
            } catch (\ValueError $e) {
                throw new ValidationException('Category must be one of: HOME, WORK, PROVINCIAL');
            }
        }
    }

    /**
     * Sets the country for this address.
     *
     * @param  string  $country  ISO 3166-2 country code (2 letters)
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
     * @param  string|null  $category  Must be one of: HOME, WORK, PROVINCIAL
     * @return $this
     *
     * @throws ValidationException
     */
    public function setCategory(?string $category): self
    {
        $this->validateCategory($category);
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
            $array['category'] = $this->category;
        }

        if ($this->isPrimary) {
            $array['is_primary'] = $this->isPrimary;
        }

        return $array;
    }
}
