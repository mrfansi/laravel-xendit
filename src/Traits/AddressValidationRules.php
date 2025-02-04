<?php

namespace Mrfansi\LaravelXendit\Traits;

use Mrfansi\LaravelXendit\Enums\AddressCategory;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;

trait AddressValidationRules
{

    /**
     * Maximum length for address fields.
     */
    private const MAX_LENGTH = 255;


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

}
