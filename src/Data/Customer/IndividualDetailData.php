<?php

namespace Mrfansi\LaravelXendit\Data\Customer;

class IndividualDetailData
{
    public function __construct(
        public string $givenNames,
        public ?string $surname = null,
        public ?string $nationality = null,
        public ?string $placeOfBirth = null,
        public ?string $dateOfBirth = null,
        public ?string $gender = null,
    ) {}
}
