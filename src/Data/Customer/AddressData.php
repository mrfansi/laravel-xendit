<?php

namespace Mrfansi\LaravelXendit\Data\Customer;

class AddressData
{
    public function __construct(
        public string  $country,
        public ?string $provinceState = null,
        public ?string $city = null,
        public ?string $streetLine1 = null,
        public ?string $streetLine2 = null,
        public ?string $postalCode = null,
        public ?string $category = null,
        public ?bool   $isPrimary = false,
    )
    {
    }

    /**
     * Sets the country for this address.
     *
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Sets the province or state for this address.
     *
     * @param string|null $provinceState
     * @return $this
     */
    public function setProvinceState(?string $provinceState): self
    {
        $this->provinceState = $provinceState;
        return $this;
    }

    /**
     * Sets the city for this address.
     *
     * @param string|null $city
     * @return $this
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Sets the first line of the street address.
     *
     * @param string|null $streetLine1
     * @return $this
     */
    public function setStreetLine1(?string $streetLine1): self
    {
        $this->streetLine1 = $streetLine1;
        return $this;
    }

    /**
     * Sets the second line of the street address.
     *
     * @param string|null $streetLine2
     * @return $this
     */
    public function setStreetLine2(?string $streetLine2): self
    {
        $this->streetLine2 = $streetLine2;
        return $this;
    }

    /**
     * Sets the postal code for this address.
     *
     * @param string|null $postalCode The postal code for this address
     * @return $this
     */
    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }


    /**
     * Sets the category for this address.
     *
     * Categories are arbitrary but can be used to group addresses
     * by type, such as "home", "work", etc.
     *
     * @param string|null $category The category for this address
     * @return $this
     */
    public function setCategory(?string $category): self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Sets whether this address is the primary address for the customer.
     *
     * @param bool|null $isPrimary Whether this address is the primary address
     * @return $this
     */
    public function setIsPrimary(?bool $isPrimary): self
    {
        $this->isPrimary = $isPrimary;
        return $this;
    }

}
