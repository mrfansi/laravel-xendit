<?php

namespace Mrfansi\Xendit\Data;

use Mrfansi\Xendit\Data\Abstracts\AbstractDataTransferObject;

/**
 * Class Address
 *
 * Represents a physical address with its details
 */
class Address extends AbstractDataTransferObject
{
    /**
     * @param  string|null  $country  Country code (e.g., 'ID' for Indonesia)
     * @param  string|null  $streetLine1  First line of street address
     * @param  string|null  $streetLine2  Second line of street address (optional)
     * @param  string|null  $city  City name
     * @param  string|null  $province  Province or state name
     * @param  string|null  $state  Alias for province
     * @param  string|null  $postalCode  Postal or ZIP code
     */
    public function __construct(
        public ?string $country = null,
        public ?string $streetLine1 = null,
        public ?string $streetLine2 = null,
        public ?string $city = null,
        public ?string $province = null,
        public ?string $state = null,
        public ?string $postalCode = null,
    ) {}
}
