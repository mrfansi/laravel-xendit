<?php

namespace Mrfansi\LaravelXendit\Data\Customer;

use Mrfansi\LaravelXendit\Traits\IdentityAccountValidationRules;

class IdentityAccountData
{
    use IdentityAccountValidationRules;

    public function __construct(
        public string $type,
        public ?string $company = null,
        public ?string $description = null,
        public ?string $country = null,
        public ?array $properties = null,
    ) {
        $this->validateData();
    }

    private function validateData(): void
    {
        $this->validateType($this->type);
        $this->validateCompany($this->company);
        $this->validateDescription($this->description);
        $this->validateCountry($this->country);
        $this->validateProperties($this->properties, $this->type);
    }

    public function setType(string $type): self
    {
        $this->validateType($type);
        $this->type = $type;

        return $this;
    }

    public function setCompany(?string $company): self
    {
        $this->validateCompany($company);
        $this->company = $company;

        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->validateDescription($description);
        $this->description = $description;

        return $this;
    }

    public function setCountry(?string $country): self
    {
        $this->validateCountry($country);
        $this->country = $country;

        return $this;
    }

    public function setProperties(?array $properties): self
    {
        if ($properties !== null) {
            $this->validateProperties($properties, $this->type);
        }
        $this->properties = $properties;

        return $this;
    }

    public function toArray(): array
    {
        $array = [
            'type' => $this->type,
        ];

        if ($this->company !== null) {
            $array['company'] = $this->company;
        }

        if ($this->description !== null) {
            $array['description'] = $this->description;
        }

        if ($this->country !== null) {
            $array['country'] = $this->country;
        }

        if ($this->properties !== null) {
            $array['properties'] = $this->properties;
        }

        return $array;
    }
}
