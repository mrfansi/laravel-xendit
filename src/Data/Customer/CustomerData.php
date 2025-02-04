<?php

namespace Mrfansi\LaravelXendit\Data\Customer;

use Illuminate\Support\Collection;

class CustomerData
{
    public function __construct(
        public string $referenceId,
        public string $type,
        public ?IndividualDetailData $individualDetail = null,
        public ?BusinessDetailData $businessDetail = null,
        public ?string $mobileNumber = null,
        public ?string $phoneNumber = null,
        public ?string $email = null,
        /** @var Collection<AddressData> */
        public ?array $addresses = null,
    ) {}
}
