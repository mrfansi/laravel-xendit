<?php

namespace Mrfansi\LaravelXendit\Data\Customer;

use Mrfansi\LaravelXendit\Exceptions\ValidationException;
use Mrfansi\LaravelXendit\Traits\CustomerValidationRules;

class CustomerData
{
    use CustomerValidationRules;

    public function __construct(
        public string $referenceId,
        public string $type,
        public ?IndividualDetailData $individualDetail = null,
        public ?BusinessDetailData $businessDetail = null,
        public ?string $mobileNumber = null,
        public ?string $phoneNumber = null,
        public ?string $hashedPhoneNumber = null,
        public ?string $email = null,
        /** @var AddressData[] */
        public ?array $addresses = null,
        /** @var IdentityAccountData[] */
        public ?array $identityAccounts = null,
        /** @var KycDocumentData[] */
        public ?array $kycDocuments = null,
        public ?string $description = null,
        public ?string $dateOfRegistration = null,
        public ?string $domicileOfRegistration = null,
        public ?array $metadata = null,
    ) {
        $this->validateData();
    }

    private function validateData(): void
    {
        $this->validateReferenceId($this->referenceId);
        $this->validateType($this->type);
        $this->validateMobileNumber($this->mobileNumber);
        $this->validatePhoneNumber($this->phoneNumber);
        $this->validateEmail($this->email);
        $this->validateDescription($this->description);
        $this->validateDateOfRegistration($this->dateOfRegistration);
        $this->validateDomicileOfRegistration($this->domicileOfRegistration);

        // Validate type-specific details
        if ($this->type === 'INDIVIDUAL' && $this->individualDetail === null) {
            throw new ValidationException('Individual detail is required when type is INDIVIDUAL');
        }
        if ($this->type === 'BUSINESS' && $this->businessDetail === null) {
            throw new ValidationException('Business detail is required when type is BUSINESS');
        }
    }

    public function setReferenceId(string $referenceId): self
    {
        $this->validateReferenceId($referenceId);
        $this->referenceId = $referenceId;

        return $this;
    }

    public function setType(string $type): self
    {
        $this->validateType($type);
        $this->type = $type;

        return $this;
    }

    public function setIndividualDetail(?IndividualDetailData $individualDetail): self
    {
        $this->individualDetail = $individualDetail;

        return $this;
    }

    public function setBusinessDetail(?BusinessDetailData $businessDetail): self
    {
        $this->businessDetail = $businessDetail;

        return $this;
    }

    public function setMobileNumber(?string $mobileNumber): self
    {
        $this->validateMobileNumber($mobileNumber);
        $this->mobileNumber = $mobileNumber;

        return $this;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->validatePhoneNumber($phoneNumber);
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function setHashedPhoneNumber(?string $hashedPhoneNumber): self
    {
        $this->hashedPhoneNumber = $hashedPhoneNumber;

        return $this;
    }

    public function setEmail(?string $email): self
    {
        $this->validateEmail($email);
        $this->email = $email;

        return $this;
    }

    public function setAddresses(?array $addresses): self
    {
        $this->addresses = $addresses;

        return $this;
    }

    public function setIdentityAccounts(?array $identityAccounts): self
    {
        $this->identityAccounts = $identityAccounts;

        return $this;
    }

    public function setKycDocuments(?array $kycDocuments): self
    {
        $this->kycDocuments = $kycDocuments;

        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->validateDescription($description);
        $this->description = $description;

        return $this;
    }

    public function setDateOfRegistration(?string $dateOfRegistration): self
    {
        $this->validateDateOfRegistration($dateOfRegistration);
        $this->dateOfRegistration = $dateOfRegistration;

        return $this;
    }

    public function setDomicileOfRegistration(?string $domicileOfRegistration): self
    {
        $this->validateDomicileOfRegistration($domicileOfRegistration);
        $this->domicileOfRegistration = $domicileOfRegistration;

        return $this;
    }

    public function setMetadata(?array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function toArray(): array
    {
        $array = [
            'reference_id' => $this->referenceId,
            'type' => $this->type,
        ];

        if ($this->individualDetail !== null) {
            $array['individual_detail'] = $this->individualDetail->toArray();
        }

        if ($this->businessDetail !== null) {
            $array['business_detail'] = $this->businessDetail->toArray();
        }

        if ($this->mobileNumber !== null) {
            $array['mobile_number'] = $this->mobileNumber;
        }

        if ($this->phoneNumber !== null) {
            $array['phone_number'] = $this->phoneNumber;
        }

        if ($this->hashedPhoneNumber !== null) {
            $array['hashed_phone_number'] = $this->hashedPhoneNumber;
        }

        if ($this->email !== null) {
            $array['email'] = $this->email;
        }

        if ($this->addresses !== null) {
            $array['addresses'] = array_map(fn ($address) => $address->toArray(), $this->addresses);
        }

        if ($this->identityAccounts !== null) {
            $array['identity_accounts'] = array_map(fn ($account) => $account->toArray(), $this->identityAccounts);
        }

        if ($this->kycDocuments !== null) {
            $array['kyc_documents'] = array_map(fn ($document) => $document->toArray(), $this->kycDocuments);
        }

        if ($this->description !== null) {
            $array['description'] = $this->description;
        }

        if ($this->dateOfRegistration !== null) {
            $array['date_of_registration'] = $this->dateOfRegistration;
        }

        if ($this->domicileOfRegistration !== null) {
            $array['domicile_of_registration'] = $this->domicileOfRegistration;
        }

        if ($this->metadata !== null) {
            $array['metadata'] = $this->metadata;
        }

        return $array;
    }
}
