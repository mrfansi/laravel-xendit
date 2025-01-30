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
     * @param  string|null  $type  Type of customer (e.g., 'INDIVIDUAL', 'BUSINESS')
     * @param  string|null  $referenceId  Customer's reference ID in your system
     * @param  string|null  $individualFirstName  First name for individual customers
     * @param  string|null  $individualLastName  Last name for individual customers
     * @param  string|null  $individualEmail  Email address for individual customers
     * @param  string|null  $individualMobileNumber  Mobile number for individual customers
     * @param  string|null  $businessName  Business name for business customers
     * @param  string|null  $businessEmail  Business email for business customers
     * @param  string|null  $businessMobileNumber  Business mobile number for business customers
     * @param  array<Address>|null  $addresses  List of customer addresses
     * @param  string|null  $description  Additional description about the customer
     */
    public function __construct(
        public ?string $type = null,
        public ?string $referenceId = null,
        public ?string $individualFirstName = null,
        public ?string $individualLastName = null,
        public ?string $individualEmail = null,
        public ?string $individualMobileNumber = null,
        public ?string $businessName = null,
        public ?string $businessEmail = null,
        public ?string $businessMobileNumber = null,
        public ?array $addresses = null,
        public ?string $description = null,
    ) {}
}
