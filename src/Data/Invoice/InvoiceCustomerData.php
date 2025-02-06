<?php

namespace Mrfansi\LaravelXendit\Data\Invoice;

use Mrfansi\LaravelXendit\Data\Customer\AddressData;
use Mrfansi\LaravelXendit\Data\Customer\IndividualDetailData;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;

class InvoiceCustomerData extends IndividualDetailData
{
    public function __construct(
        public string $givenNames,
        public ?string $surname = null,
        public ?string $email = null,
        public ?string $mobileNumber = null,
        public ?string $nationality = null,
        public ?string $placeOfBirth = null,
        public ?string $dateOfBirth = null,
        public ?string $gender = null,
        /** @var AddressData[] */
        public ?array $addresses = null,
    ) {
        $this->validateEmail($email);
        $this->mobileNumber = $this->cleanMobileNumber($mobileNumber);
        $this->validateMobileNumber($this->mobileNumber);
        $this->validateAddresses($addresses);

        parent::__construct($givenNames, $surname, $nationality, $placeOfBirth, $dateOfBirth, $gender);

    }

    /**
     * Clean the mobile number by removing double plus signs.
     */
    private function cleanMobileNumber(?string $mobileNumber): ?string
    {
        if ($mobileNumber === null) {
            return null;
        }

        // Replace multiple '+' signs with a single '+'
        return preg_replace('/\++/', '+', $mobileNumber);
    }

    private function validateEmail(?string $email): void
    {
        if ($email !== null && ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException("Invalid email format. Found: $email");
        }
    }

    private function validateMobileNumber(?string $mobileNumber): void
    {
        if ($mobileNumber !== null && ! preg_match('/^\+[1-9]\d{1,14}$/', $mobileNumber)) {
            throw new ValidationException("Mobile number must be in E164 format (e.g., +6281234567890). Found: $mobileNumber");
        }
    }

    private function validateAddresses(?array $addresses): void
    {
        if ($addresses !== null) {
            foreach ($addresses as $address) {
                if (! ($address instanceof AddressData)) {
                    throw new ValidationException('Each address must be an instance of AddressData');
                }
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $array = parent::toArray();

        if ($this->email !== null) {
            $array['email'] = $this->email;
        }

        if ($this->mobileNumber !== null) {
            $array['mobile_number'] = $this->mobileNumber;
        }

        if ($this->addresses !== null) {
            $array['addresses'] = array_map(fn (AddressData $address) => $address->toArray(), $this->addresses);
        }

        return $array;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            givenNames: $data['given_names'],
            surname: $data['surname'] ?? null,
            email: $data['email'] ?? null,
            mobileNumber: $data['mobile_number'] ?? null,
            nationality: $data['nationality'] ?? null,
            placeOfBirth: $data['place_of_birth'] ?? null,
            dateOfBirth: $data['date_of_birth'] ?? null,
            gender: $data['gender'] ?? null,
            addresses: isset($data['addresses']) ? array_map(fn ($address) => AddressData::fromArray($address), $data['addresses']) : null,
        );
    }
}
