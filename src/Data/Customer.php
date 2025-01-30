<?php

namespace Mrfansi\Xendit\Data;

use Mrfansi\Xendit\Data\Abstracts\AbstractDataTransferObject;

/**
 * Class Customer
 *
 * Represents a customer with their details including addresses and tax information
 */
class Customer extends AbstractDataTransferObject
{
    /**
     * @param  string|null  $given_names  Customer's given names
     * @param  string|null  $surname  Customer's surname
     * @param  string|null  $email  Customer's email address
     * @param  string|null  $mobile_number  Customer's mobile number
     * @param  array<Address>|null  $addresses  List of customer addresses
     * @param  string|null  $description  Additional description about the customer
     */
    public function __construct(
        public ?string $given_names = null,
        public ?string $surname = null,
        public ?string $email = null,
        public ?string $mobile_number = null,
        public ?array $addresses = null,
        public ?string $description = null,
    ) {}
}
