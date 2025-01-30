<?php

namespace Mrfansi\Xendit\Data;

use Mrfansi\Xendit\Data\Abstracts\AbstractDataTransferObject;

/**
 * Class Customer
 *
 * Represents a customer with their details including addresses
 */
class Customer extends AbstractDataTransferObject
{
    /**
     * @param  string|null  $given_names  Customer's given names
     * @param  string|null  $surname  Customer's surname
     * @param  string|null  $email  Customer's email address
     * @param  string|null  $mobile_number  Customer's mobile number
     * @param  Address[]|null  $addresses  List of customer addresses
     */
    public function __construct(
        public ?string $given_names = null,
        public ?string $surname = null,
        public ?string $email = null,
        public ?string $mobile_number = null,
        /** @var Address[]|null */
        public ?array $addresses = null,
    ) {}
}
