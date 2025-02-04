<?php

namespace Mrfansi\LaravelXendit\Traits;

use Mrfansi\LaravelXendit\Exceptions\ValidationException;

trait CustomerValidationRules
{
    /**
     * Maximum length for customer fields.
     */
    private const MAX_LENGTH = 255;

    private const MAX_EMAIL_LENGTH = 50;

    private const MAX_PHONE_LENGTH = 50;

    private const MAX_DESCRIPTION_LENGTH = 500;

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
     * Validates the reference ID field.
     *
     * @throws ValidationException
     */
    private function validateReferenceId(string $value): void
    {
        if (trim($value) === '') {
            throw new ValidationException('Reference ID is required');
        }
        if (strlen($value) > self::MAX_LENGTH) {
            throw new ValidationException('Reference ID must not exceed 255 characters');
        }
        if (! $this->isAlphanumeric($value)) {
            throw new ValidationException('Reference ID must be alphanumeric');
        }
    }

    /**
     * Validates the type field.
     *
     * @throws ValidationException
     */
    private function validateType(string $value): void
    {
        $validTypes = ['INDIVIDUAL', 'BUSINESS'];
        if (! in_array($value, $validTypes)) {
            throw new ValidationException('Type must be one of: '.implode(', ', $validTypes));
        }
    }

    /**
     * Validates the mobile number field.
     *
     * @throws ValidationException
     */
    private function validateMobileNumber(?string $value): void
    {
        if ($value !== null) {
            if (strlen($value) > self::MAX_PHONE_LENGTH) {
                throw new ValidationException('Mobile number must not exceed 50 characters');
            }
            if (! preg_match('/^\+[1-9]\d{1,14}$/', $value)) {
                throw new ValidationException('Mobile number must be in E.164 format');
            }
        }
    }

    /**
     * Validates the phone number field.
     *
     * @throws ValidationException
     */
    private function validatePhoneNumber(?string $value): void
    {
        if ($value !== null) {
            if (strlen($value) > self::MAX_PHONE_LENGTH) {
                throw new ValidationException('Phone number must not exceed 50 characters');
            }
            if (! preg_match('/^\+[1-9]\d{1,14}$/', $value)) {
                throw new ValidationException('Phone number must be in E.164 format');
            }
        }
    }

    /**
     * Validates the email field.
     *
     * @throws ValidationException
     */
    private function validateEmail(?string $value): void
    {
        if ($value !== null) {
            if (strlen($value) > self::MAX_EMAIL_LENGTH) {
                throw new ValidationException('Email must not exceed 50 characters');
            }
            if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
                throw new ValidationException('Email must be a valid email address');
            }
        }
    }

    /**
     * Validates the description field.
     *
     * @throws ValidationException
     */
    private function validateDescription(?string $value): void
    {
        if ($value !== null) {
            if (strlen($value) > self::MAX_DESCRIPTION_LENGTH) {
                throw new ValidationException('Description must not exceed 500 characters');
            }
            if (! $this->isAlphanumeric($value)) {
                throw new ValidationException('Description must be alphanumeric');
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

    /**
     * Validates the domicile of registration field.
     *
     * @throws ValidationException
     */
    private function validateDomicileOfRegistration(?string $value): void
    {
        if ($value !== null) {
            if (strlen($value) !== 2 || ! ctype_alpha($value)) {
                throw new ValidationException('Domicile of registration must be a valid ISO 3166-2 code (2 letters)');
            }
        }
    }
}
