<?php

namespace Mrfansi\LaravelXendit\Data;

use Mrfansi\LaravelXendit\Data\Abstracts\AbstractDataTransferObject;

/**
 * Class Address
 *
 * Represents a physical address with its details
 */
class Address extends AbstractDataTransferObject
{
    /**
     * @param  string|null  $city  City name
     * @param  string|null  $country  Country code (e.g., 'ID' for Indonesia)
     * @param  string|null  $postal_code  Postal or ZIP code
     * @param  string|null  $street_line1  First line of street address
     * @param  string|null  $street_line2  Second line of street address (optional)
     */
    public function __construct(
        public ?string $city = null,
        public ?string $country = null,
        public ?string $postal_code = null,
        public ?string $street_line1 = null,
        public ?string $street_line2 = null,
    ) {}
}
