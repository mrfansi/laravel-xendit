<?php

namespace Mrfansi\LaravelXendit\Traits;

use Mrfansi\LaravelXendit\Exceptions\ValidationException;

trait KycDocumentValidationRules
{
    /**
     * Maximum length for KYC document fields.
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

        return preg_match('/^[a-zA-Z0-9\s]+$/', $value) === 1;
    }

    /**
     * Validates the country field.
     *
     * @throws ValidationException
     */
    private function validateCountry(string $value): void
    {
        if ($value != null) {
            if (strlen($value) !== 2 || ! ctype_alpha($value)) {
                throw new ValidationException('Country must be a valid ISO 3166-2 code (2 letters)');
            }
        }
    }

    /**
     * Validates the type field.
     *
     * @throws ValidationException
     */
    private function validateType(string $value): void
    {
        $validTypes = [
            'BIRTH_CERTIFICATE',
            'BANK_STATEMENT',
            'DRIVING_LICENSE',
            'IDENTITY_CARD',
            'PASSPORT',
            'VISA',
            'BUSINESS_REGISTRATION',
            'BUSINESS_LICENSE',
        ];

        if (! in_array($value, $validTypes)) {
            throw new ValidationException('Type must be one of: '.implode(', ', $validTypes));
        }
    }

    /**
     * Validates the sub type field.
     *
     * @throws ValidationException
     */
    private function validateSubType(?string $value): void
    {
        if ($value !== null) {
            $validSubTypes = [
                'NATIONAL_ID',
                'CONSULAR_ID',
                'VOTER_ID',
                'POSTAL_ID',
                'RESIDENCE_PERMIT',
                'TAX_ID',
                'STUDENT_ID',
                'MILITARY_ID',
                'MEDICAL_ID',
            ];

            if (! in_array($value, $validSubTypes)) {
                throw new ValidationException('Sub type must be one of: '.implode(', ', $validSubTypes));
            }
        }
    }

    /**
     * Validates the document name field.
     *
     * @throws ValidationException
     */
    private function validateDocumentName(?string $value): void
    {
        if ($value !== null) {
            if (strlen($value) > self::MAX_LENGTH) {
                throw new ValidationException('Document name must not exceed 255 characters');
            }
            if (! $this->isAlphanumeric($value)) {
                throw new ValidationException('Document name must be alphanumeric');
            }
        }
    }

    /**
     * Validates the document number field.
     *
     * @throws ValidationException
     */
    private function validateDocumentNumber(?string $value): void
    {
        if ($value !== null) {
            if (strlen($value) > self::MAX_LENGTH) {
                throw new ValidationException('Document number must not exceed 255 characters');
            }
        }
    }

    /**
     * Validates the expires at field.
     *
     * @throws ValidationException
     */
    private function validateExpiresAt(?string $value): void
    {
        if ($value !== null) {
            if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                throw new ValidationException('Expiry date must be in YYYY-MM-DD format');
            }
        }
    }

    /**
     * Validates the holder name field.
     *
     * @throws ValidationException
     */
    private function validateHolderName(?string $value): void
    {
        if ($value !== null) {
            if (strlen($value) > self::MAX_LENGTH) {
                throw new ValidationException('Holder name must not exceed 255 characters');
            }
            if (! $this->isAlphanumeric($value)) {
                throw new ValidationException('Holder name must be alphanumeric');
            }
        }
    }

    /**
     * Validates the document images field.
     *
     * @throws ValidationException
     */
    private function validateDocumentImages(?array $value): void
    {
        if ($value !== null) {
            foreach ($value as $image) {
                if (! is_string($image)) {
                    throw new ValidationException('Document images must be an array of strings');
                }
            }
        }
    }
}
