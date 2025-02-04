<?php

namespace Mrfansi\LaravelXendit\Data\Customer;

use Mrfansi\LaravelXendit\Exceptions\ValidationException;

class IndividualDetailData
{
    public function __construct(
        public string $givenNames,
        public ?string $surname = null,
        public ?string $nationality = null,
        public ?string $placeOfBirth = null,
        public ?string $dateOfBirth = null,
        public ?string $gender = null,
    ) {
        $this->validate();
    }

    /**
     * Sets the given names and returns self.
     *
     * @param  string  $givenNames  Given names of the customer.
     * @return $this
     */
    public function setGivenNames(string $givenNames): self
    {
        $this->validateGivenNames($givenNames);
        $this->givenNames = $givenNames;

        return $this;
    }

    /**
     * Sets the surname and returns self.
     *
     * @param  string|null  $surname  Surname of the customer.
     * @return $this
     */
    public function setSurname(?string $surname): self
    {
        $this->validateSurname($surname);
        $this->surname = $surname;

        return $this;

    }

    /**
     * Sets the nationality and returns self.
     *
     * @param  string|null  $nationality  Nationality of the customer.
     * @return $this
     */
    public function setNationality(?string $nationality): self
    {
        $this->validateNationality($nationality);
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * Sets the place of birth and returns self.
     *
     * @param  string|null  $placeOfBirth  Place of birth of the customer.
     * @return $this
     */
    public function setPlaceOfBirth(?string $placeOfBirth): self
    {
        $this->validatePlaceOfBirth($placeOfBirth);
        $this->placeOfBirth = $placeOfBirth;

        return $this;
    }

    /**
     * Sets the date of birth and returns self.
     *
     * @param  string|null  $dateOfBirth  Date of birth of the customer in 'YYYY-MM-DD' format.
     * @return $this
     */
    public function setDateOfBirth(?string $dateOfBirth): self
    {
        $this->validateDateOfBirth($dateOfBirth);
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Sets the gender and returns self.
     *
     * @param  string|null  $gender  Gender of the customer, either 'MALE', 'FEMALE', or 'OTHER'.
     * @return $this
     */
    public function setGender(?string $gender): self
    {
        $this->validateGender($gender);
        $this->gender = $gender;

        return $this;
    }

    /**
     * Converts the object to an array, excluding null values.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $array = [
            'given_names' => $this->givenNames,
        ];

        if ($this->surname !== null) {
            $array['surname'] = $this->surname;
        }

        if ($this->nationality !== null) {
            $array['nationality'] = $this->nationality;
        }

        if ($this->placeOfBirth !== null) {
            $array['place_of_birth'] = $this->placeOfBirth;
        }

        if ($this->dateOfBirth !== null) {
            $array['date_of_birth'] = $this->dateOfBirth;
        }

        if ($this->gender !== null) {
            $array['gender'] = $this->gender;
        }

        return $array;
    }

    private function validate(): void
    {
        $this->validateGivenNames($this->givenNames);
        $this->validateSurname($this->surname);
        $this->validateNationality($this->nationality);
        $this->validatePlaceOfBirth($this->placeOfBirth);
        $this->validateDateOfBirth($this->dateOfBirth);
        $this->validateGender($this->gender);
    }

    private function validateGivenNames(string $givenNames): void
    {
        if (empty($givenNames)) {
            throw new ValidationException('Given names is required');
        }

        if (! preg_match('/^[a-zA-Z0-9\s]+$/', $givenNames)) {
            throw new ValidationException('Given names must be alphanumeric');
        }
    }

    private function validateSurname(?string $surname): void
    {
        if ($surname !== null && ! preg_match('/^[a-zA-Z0-9\s]+$/', $surname)) {
            throw new ValidationException('Surname must be alphanumeric');
        }
    }

    private function validateNationality(?string $nationality): void
    {
        if ($nationality !== null && ! preg_match('/^[A-Z]{2}$/', $nationality)) {
            throw new ValidationException('Nationality must be a valid ISO 3166-2 code (2 letters)');
        }
    }

    private function validatePlaceOfBirth(?string $placeOfBirth): void
    {
        if ($placeOfBirth !== null && ! preg_match('/^[a-zA-Z0-9\s]+$/', $placeOfBirth)) {
            throw new ValidationException('Place of birth must be alphanumeric');
        }
    }

    private function validateDateOfBirth(?string $dateOfBirth): void
    {
        if ($dateOfBirth !== null) {
            if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateOfBirth)) {
                throw new ValidationException('Date of birth must be in YYYY-MM-DD format');
            }

            if (! strtotime($dateOfBirth)) {
                throw new ValidationException('Invalid date of birth');
            }
        }
    }

    private function validateGender(?string $gender): void
    {
        if ($gender !== null && ! in_array($gender, ['MALE', 'FEMALE', 'OTHER'], true)) {
            throw new ValidationException('Gender must be one of: MALE, FEMALE, OTHER');
        }
    }
}
