<?php

namespace Mrfansi\XenditSdk\Data;

use InvalidArgumentException;
use Spatie\LaravelData\Data;

class Customer extends Data
{
    /**
     * Customer information.
     *
     * @param  string|null  $given_names  Given name(s) of the customer
     * @param  string|null  $surname  Surname of the customer
     * @param  string|null  $email  Email address of the customer in valid format
     * @param  string|null  $mobile_number  Mobile phone number in E164 format (e.g., +6281234567890)
     * @param  Address[]|null  $addresses  Array of customer addresses
     *
     * @throws InvalidArgumentException When email or mobile number format is invalid
     */
    public function __construct(
        public readonly ?string $given_names,
        public readonly ?string $surname,
        public readonly ?string $email,
        public readonly ?string $mobile_number,
        /** @var Address[]|null */
        public readonly ?array $addresses,
    ) {
        if ($this->email !== null && ! filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }

        if ($this->mobile_number !== null && ! preg_match('/^\+[1-9]\d{1,14}$/', $this->mobile_number)) {
            throw new InvalidArgumentException('Mobile number must be in E164 format (e.g., +6281234567890)');
        }
    }

    /**
     * Creates a Customer instance from an array.
     *
     * @param  array<string, mixed>  $data  The array containing customer data
     */
    public static function fromArray(array $data): self
    {
        $addresses = null;
        if (isset($data['addresses']) && is_array($data['addresses'])) {
            $addresses = array_map(
                fn (array $address) => Address::fromArray($address),
                $data['addresses']
            );
        }

        return new self(
            given_names: $data['given_names'] ?? null,
            surname: $data['surname'] ?? null,
            email: $data['email'] ?? null,
            mobile_number: $data['mobile_number'] ?? null,
            addresses: $addresses,
        );
    }

    /**
     * Converts the Customer instance to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'given_names' => $this->given_names,
            'surname' => $this->surname,
            'email' => $this->email,
            'mobile_number' => $this->mobile_number,
            'addresses' => $this->addresses ? array_map(
                fn (Address $address) => $address->toArray(),
                $this->addresses
            ) : null,
        ], fn ($value) => $value !== null);
    }
}
