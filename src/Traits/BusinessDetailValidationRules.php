<?php

namespace Mrfansi\LaravelXendit\Traits;

use Mrfansi\LaravelXendit\Enums\BusinessType;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;

trait BusinessDetailValidationRules
{
    /**
     * Maximum length for business detail fields.
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

        // Allow only letters, numbers and spaces
        return preg_match('/^[a-zA-Z0-9\s]+$/', $value) === 1;
    }

    /**
     * Validates the business name field.
     *
     * @throws ValidationException
     */
    private function validateBusinessName(string $value): void
    {
        if (trim($value) === '') {
            throw new ValidationException('Business name is required');
        }
        if (strlen($value) > self::MAX_LENGTH) {
            throw new ValidationException('Business name must not exceed 255 characters');
        }
        if (! $this->isAlphanumeric($value)) {
            throw new ValidationException('Business name must be alphanumeric');
        }
    }

    /**
     * Validates the business type field.
     *
     * @throws ValidationException
     */
    private function validateBusinessType(string $value): void
    {
        try {
            if (! in_array($value, array_column(BusinessType::cases(), 'value'))) {
                throw new ValidationException('Business type must be one of: '.implode(', ', array_column(BusinessType::cases(), 'value')));
            }
        } catch (\ValueError $e) {
            throw new ValidationException('Business type must be one of: '.implode(', ', array_column(BusinessType::cases(), 'value')));
        }
    }

    /**
     * Validates the trading name field.
     *
     * @throws ValidationException
     */
    private function validateTradingName(?string $value): void
    {
        if ($value !== null) {
            if (strlen($value) > self::MAX_LENGTH) {
                throw new ValidationException('Trading name must not exceed 255 characters');
            }
            if (! $this->isAlphanumeric($value)) {
                throw new ValidationException('Trading name must be alphanumeric');
            }
        }
    }

    /**
     * Validates the nature of business field.
     *
     * @throws ValidationException
     */
    private function validateNatureOfBusiness(?string $value): void
    {
        if ($value !== null) {
            if (strlen($value) > self::MAX_LENGTH) {
                throw new ValidationException('Nature of business must not exceed 255 characters');
            }
            if (! $this->isAlphanumeric($value)) {
                throw new ValidationException('Nature of business must be alphanumeric');
            }
        }
    }

    /**
     * Validates the business domicile field.
     *
     * @throws ValidationException
     */
    private function validateBusinessDomicile(?string $value): void
    {
        if ($value !== null) {
            if (strlen($value) !== 2 || ! ctype_alpha($value)) {
                throw new ValidationException('Business domicile must be a valid ISO 3166-2 code (2 letters)');
            }
        }
    }

    /**
     * Validates the date of registration field.
     *
     * @throws ValidationException
     */
    private function validateDateOfRegistration(?string $value): void
    {
        if ($value !== null) {
            if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                throw new ValidationException('Date of registration must be in YYYY-MM-DD format');
            }
        }
    }
}
