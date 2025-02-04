<?php

namespace Mrfansi\LaravelXendit\Data\Customer;

use Mrfansi\LaravelXendit\Enums\BusinessType;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;
use Mrfansi\LaravelXendit\Traits\BusinessDetailValidationRules;

/**
 * Represents a business details for a customer.
 */
class BusinessDetailData
{
    use BusinessDetailValidationRules;

    public function __construct(
        public string $businessName,
        public BusinessType|string $businessType,
        public ?string $tradingName = null,
        public ?string $natureOfBusiness = null,
        public ?string $businessDomicile = null,
        public ?string $dateOfRegistration = null,
    ) {
        $this->validateBusinessName($businessName);
        $this->validateBusinessType($businessType);
        $this->validateTradingName($tradingName);
        $this->validateNatureOfBusiness($natureOfBusiness);
        $this->validateBusinessDomicile($businessDomicile);
        $this->validateDateOfRegistration($dateOfRegistration);
    }

    /**
     * Sets the business name of the customer.
     *
     * @param  string  $businessName  Maximum 255 characters, alphanumeric only
     *
     * @throws ValidationException
     */
    public function setBusinessName(string $businessName): self
    {
        $this->validateBusinessName($businessName);
        $this->businessName = $businessName;

        return $this;
    }

    /**
     * Sets the business type of the customer.
     *
     * @param  BusinessType|string  $businessType  Must be a valid BusinessType enum or its string value
     *
     * @throws ValidationException
     */
    public function setBusinessType(BusinessType|string $businessType): self
    {
        $this->validateBusinessType($businessType);
        $this->businessType = $businessType;

        return $this;
    }

    /**
     * Sets the trading name of the customer.
     *
     * @param  string|null  $tradingName  Maximum 255 characters, alphanumeric only
     *
     * @throws ValidationException
     */
    public function setTradingName(?string $tradingName): self
    {
        $this->validateTradingName($tradingName);
        $this->tradingName = $tradingName;

        return $this;
    }

    /**
     * Sets the nature of the business of the customer.
     *
     * @param  string|null  $natureOfBusiness  Maximum 255 characters, alphanumeric only
     *
     * @throws ValidationException
     */
    public function setNatureOfBusiness(?string $natureOfBusiness): self
    {
        $this->validateNatureOfBusiness($natureOfBusiness);
        $this->natureOfBusiness = $natureOfBusiness;

        return $this;
    }

    /**
     * Sets the business domicile of the customer.
     *
     * @param  string|null  $businessDomicile  Maximum 255 characters, alphanumeric only
     *
     * @throws ValidationException
     */
    public function setBusinessDomicile(?string $businessDomicile): self
    {
        $this->validateBusinessDomicile($businessDomicile);
        $this->businessDomicile = $businessDomicile;

        return $this;
    }

    /**
     * Sets the date of registration of the business of the customer.
     *
     * @param  string|null  $dateOfRegistration  Must be in YYYY-MM-DD format
     *
     * @throws ValidationException
     */
    public function setDateOfRegistration(?string $dateOfRegistration): self
    {
        $this->validateDateOfRegistration($dateOfRegistration);
        $this->dateOfRegistration = $dateOfRegistration;

        return $this;
    }

    /**
     * Converts the business detail data to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $array = [
            'business_name' => $this->businessName,
            'business_type' => $this->businessType instanceof BusinessType ? $this->businessType->value : $this->businessType,
        ];

        if ($this->tradingName !== null) {
            $array['trading_name'] = $this->tradingName;
        }

        if ($this->natureOfBusiness !== null) {
            $array['nature_of_business'] = $this->natureOfBusiness;
        }

        if ($this->businessDomicile !== null) {
            $array['business_domicile'] = $this->businessDomicile;
        }

        if ($this->dateOfRegistration !== null) {
            $array['date_of_registration'] = $this->dateOfRegistration;
        }

        return $array;
    }
}
