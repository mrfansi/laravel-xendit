<?php

namespace Mrfansi\LaravelXendit\Traits;

use Mrfansi\LaravelXendit\Exceptions\ValidationException;

trait IdentityAccountValidationRules
{
    /**
     * Maximum length for identity account fields.
     */
    private const MAX_LENGTH = 255;

    private const MAX_COMPANY_LENGTH = 100;

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
     * Validates the type field.
     *
     * @throws ValidationException
     */
    private function validateType(string $value): void
    {
        $validTypes = [
            'BANK_ACCOUNT',
            'EWALLET',
            'CREDIT_CARD',
            'PAY_LATER',
            'OTC',
            'QR_CODE',
            'SOCIAL_MEDIA',
        ];

        if (! in_array($value, $validTypes)) {
            throw new ValidationException('Type must be one of: '.implode(', ', $validTypes));
        }
    }

    /**
     * Validates the company field.
     *
     * @throws ValidationException
     */
    private function validateCompany(?string $value): void
    {
        if ($value !== null) {
            if (strlen($value) > self::MAX_COMPANY_LENGTH) {
                throw new ValidationException('Company must not exceed 100 characters');
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
            if (strlen($value) > self::MAX_LENGTH) {
                throw new ValidationException('Description must not exceed 255 characters');
            }
            if (! $this->isAlphanumeric($value)) {
                throw new ValidationException('Description must be alphanumeric');
            }
        }
    }

    /**
     * Validates the country field.
     *
     * @throws ValidationException
     */
    private function validateCountry(?string $value): void
    {
        if ($value !== null) {
            if (strlen($value) !== 2 || ! ctype_alpha($value)) {
                throw new ValidationException('Country must be a valid ISO 3166-2 code (2 letters)');
            }
        }
    }

    /**
     * Validates the property field.
     *
     * @throws ValidationException
     */
    private function validateProperties(?array $value, string $type): void
    {
        if ($value === null) {
            return;
        }

        switch ($type) {
            case 'BANK_ACCOUNT':
                $this->validateBankAccountProperties($value);
                break;
            case 'EWALLET':
                $this->validateEWalletProperties($value);
                break;
            case 'CREDIT_CARD':
                $this->validateCreditCardProperties($value);
                break;
            case 'OTC':
                $this->validateOTCProperties($value);
                break;
            case 'QR_CODE':
                $this->validateQRCodeProperties($value);
                break;
            case 'PAY_LATER':
                $this->validatePayLaterProperties($value);
                break;
            case 'SOCIAL_MEDIA':
                $this->validateSocialMediaProperties($value);
                break;
        }
    }

    private function validateBankAccountProperties(array $properties): void
    {
        if (! isset($properties['account_number'])) {
            throw new ValidationException('Bank account number is required');
        }
        if (! isset($properties['account_holder_name'])) {
            throw new ValidationException('Bank account holder name is required');
        }
        if (! $this->isAlphanumeric($properties['account_number'])) {
            throw new ValidationException('Bank account number must be alphanumeric');
        }
        if (! $this->isAlphanumeric($properties['account_holder_name'])) {
            throw new ValidationException('Bank account holder name must be alphanumeric');
        }
        if (isset($properties['swift_code']) && ! $this->isAlphanumeric($properties['swift_code'])) {
            throw new ValidationException('Swift code must be alphanumeric');
        }
        if (isset($properties['account_type']) && ! $this->isAlphanumeric($properties['account_type'])) {
            throw new ValidationException('Account type must be alphanumeric');
        }
        if (isset($properties['account_details']) && ! $this->isAlphanumeric($properties['account_details'])) {
            throw new ValidationException('Account details must be alphanumeric');
        }
        if (isset($properties['currency']) && ! preg_match('/^[A-Z]{3}$/', $properties['currency'])) {
            throw new ValidationException('Currency must be a valid ISO 4217 code (3 letters)');
        }
    }

    private function validateEWalletProperties(array $properties): void
    {
        if (! isset($properties['account_number'])) {
            throw new ValidationException('E-wallet account number is required');
        }
        if (! $this->isAlphanumeric($properties['account_number'])) {
            throw new ValidationException('E-wallet account number must be alphanumeric');
        }
        if (isset($properties['account_holder_name']) && ! $this->isAlphanumeric($properties['account_holder_name'])) {
            throw new ValidationException('E-wallet account holder name must be alphanumeric');
        }
        if (isset($properties['currency']) && ! preg_match('/^[A-Z]{3}$/', $properties['currency'])) {
            throw new ValidationException('Currency must be a valid ISO 4217 code (3 letters)');
        }
    }

    private function validateCreditCardProperties(array $properties): void
    {
        if (! isset($properties['token_id'])) {
            throw new ValidationException('Credit card token ID is required');
        }
    }

    private function validateOTCProperties(array $properties): void
    {
        if (! isset($properties['payment_code'])) {
            throw new ValidationException('OTC payment code is required');
        }
        if (isset($properties['expires_at'])) {
            if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $properties['expires_at'])) {
                throw new ValidationException('Expiry date must be in YYYY-MM-DD format');
            }
        }
    }

    private function validateQRCodeProperties(array $properties): void
    {
        if (! isset($properties['qr_string'])) {
            throw new ValidationException('QR code string is required');
        }
    }

    private function validatePayLaterProperties(array $properties): void
    {
        if (! isset($properties['account_id'])) {
            throw new ValidationException('Pay later account ID is required');
        }
        if (isset($properties['account_holder_name']) && ! $this->isAlphanumeric($properties['account_holder_name'])) {
            throw new ValidationException('Pay later account holder name must be alphanumeric');
        }
        if (isset($properties['currency']) && ! preg_match('/^[A-Z]{3}$/', $properties['currency'])) {
            throw new ValidationException('Currency must be a valid ISO 4217 code (3 letters)');
        }
    }

    private function validateSocialMediaProperties(array $properties): void
    {
        if (! isset($properties['account_id'])) {
            throw new ValidationException('Social media account ID is required');
        }
    }
}
