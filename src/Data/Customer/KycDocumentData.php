<?php

namespace Mrfansi\LaravelXendit\Data\Customer;

use Mrfansi\LaravelXendit\Traits\KycDocumentValidationRules;

class KycDocumentData
{
    use KycDocumentValidationRules;

    public function __construct(
        public string $country,
        public string $type,
        public ?string $subType = null,
        public ?string $documentName = null,
        public ?string $documentNumber = null,
        public ?string $expiresAt = null,
        public ?string $holderName = null,
        public ?array $documentImages = null,
    ) {
        $this->validateData();
    }

    private function validateData(): void
    {
        $this->validateCountry($this->country);
        $this->validateType($this->type);
        $this->validateSubType($this->subType);
        $this->validateDocumentName($this->documentName);
        $this->validateDocumentNumber($this->documentNumber);
        $this->validateExpiresAt($this->expiresAt);
        $this->validateHolderName($this->holderName);
        $this->validateDocumentImages($this->documentImages);
    }

    public function setCountry(string $country): self
    {
        $this->validateCountry($country);
        $this->country = $country;

        return $this;
    }

    public function setType(string $type): self
    {
        $this->validateType($type);
        $this->type = $type;

        return $this;
    }

    public function setSubType(?string $subType): self
    {
        $this->validateSubType($subType);
        $this->subType = $subType;

        return $this;
    }

    public function setDocumentName(?string $documentName): self
    {
        $this->validateDocumentName($documentName);
        $this->documentName = $documentName;

        return $this;
    }

    public function setDocumentNumber(?string $documentNumber): self
    {
        $this->validateDocumentNumber($documentNumber);
        $this->documentNumber = $documentNumber;

        return $this;
    }

    public function setExpiresAt(?string $expiresAt): self
    {
        $this->validateExpiresAt($expiresAt);
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function setHolderName(?string $holderName): self
    {
        $this->validateHolderName($holderName);
        $this->holderName = $holderName;

        return $this;
    }

    public function setDocumentImages(?array $documentImages): self
    {
        $this->validateDocumentImages($documentImages);
        $this->documentImages = $documentImages;

        return $this;
    }

    public function toArray(): array
    {
        $array = [
            'country' => $this->country,
            'type' => $this->type,
        ];

        if ($this->subType !== null) {
            $array['sub_type'] = $this->subType;
        }

        if ($this->documentName !== null) {
            $array['document_name'] = $this->documentName;
        }

        if ($this->documentNumber !== null) {
            $array['document_number'] = $this->documentNumber;
        }

        if ($this->expiresAt !== null) {
            $array['expires_at'] = $this->expiresAt;
        }

        if ($this->holderName !== null) {
            $array['holder_name'] = $this->holderName;
        }

        if ($this->documentImages !== null) {
            $array['document_images'] = $this->documentImages;
        }

        return $array;
    }
}
