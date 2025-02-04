<?php

namespace Mrfansi\LaravelXendit\Data\Customer;

class BusinessDetailData
{
    public function __construct(
        public string $businessName,
        public string $businessType,
        public ?string $tradingName,
        public ?string $natureOfBusiness,
        public ?string $businessDomicile,
        public ?string $dateOfRegistration,
    ) {}
}
