<?php

use Mrfansi\LaravelXendit\Data\Customer\IndividualDetailData;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;

test('it can be instantiated with only required parameters', function () {
    $individualDetail = new IndividualDetailData(
        givenNames: 'John'
    );

    expect($individualDetail)
        ->toBeInstanceOf(IndividualDetailData::class)
        ->givenNames->toBe('John')
        ->surname->toBeNull()
        ->nationality->toBeNull()
        ->placeOfBirth->toBeNull()
        ->dateOfBirth->toBeNull()
        ->gender->toBeNull();
});

test('it can be instantiated with all parameters', function () {
    $individualDetail = new IndividualDetailData(
        givenNames: 'John',
        surname: 'Doe',
        nationality: 'US',
        placeOfBirth: 'New York',
        dateOfBirth: '1990-01-01',
        gender: 'MALE'
    );

    expect($individualDetail)
        ->toBeInstanceOf(IndividualDetailData::class)
        ->givenNames->toBe('John')
        ->surname->toBe('Doe')
        ->nationality->toBe('US')
        ->placeOfBirth->toBe('New York')
        ->dateOfBirth->toBe('1990-01-01')
        ->gender->toBe('MALE');
});

test('toArray returns only required fields when optional fields are null', function () {
    $individualDetail = new IndividualDetailData(
        givenNames: 'John'
    );

    $array = $individualDetail->toArray();

    expect($array)
        ->toBe([
            'given_names' => 'John',
        ])
        ->not->toHaveKeys([
            'surname',
            'nationality',
            'place_of_birth',
            'date_of_birth',
            'gender',
        ]);
});

test('toArray includes all non-null fields', function () {
    $individualDetail = new IndividualDetailData(
        givenNames: 'John',
        surname: 'Doe',
        nationality: 'US',
        placeOfBirth: 'New York',
        dateOfBirth: '1990-01-01',
        gender: 'MALE'
    );

    expect($individualDetail->toArray())->toBe([
        'given_names' => 'John',
        'surname' => 'Doe',
        'nationality' => 'US',
        'place_of_birth' => 'New York',
        'date_of_birth' => '1990-01-01',
        'gender' => 'MALE',
    ]);
});

test('validates given names', function () {
    // Valid given names
    expect(fn () => new IndividualDetailData('John'))
        ->not->toThrow(ValidationException::class);

    // Empty given names
    expect(fn () => new IndividualDetailData(''))
        ->toThrow(ValidationException::class, 'Given names is required');

    // Given names with special characters
    expect(fn () => new IndividualDetailData('John@Doe'))
        ->toThrow(ValidationException::class, 'Given names must be alphanumeric');
});

test('validates surname', function () {
    $individualDetail = new IndividualDetailData('John');

    // Valid surname
    expect(fn () => $individualDetail->setSurname('Doe'))
        ->not->toThrow(ValidationException::class);

    // Surname with special characters
    expect(fn () => $individualDetail->setSurname('Doe@Smith'))
        ->toThrow(ValidationException::class, 'Surname must be alphanumeric');

    // Null surname is valid
    expect(fn () => $individualDetail->setSurname(null))
        ->not->toThrow(ValidationException::class);
});

test('validates nationality', function () {
    $individualDetail = new IndividualDetailData('John');

    // Valid nationality
    expect(fn () => $individualDetail->setNationality('US'))
        ->not->toThrow(ValidationException::class);

    // Invalid nationality format
    expect(fn () => $individualDetail->setNationality('USA'))
        ->toThrow(ValidationException::class, 'Nationality must be a valid ISO 3166-2 code (2 letters)');

    // Null nationality is valid
    expect(fn () => $individualDetail->setNationality(null))
        ->not->toThrow(ValidationException::class);
});

test('validates place of birth', function () {
    $individualDetail = new IndividualDetailData('John');

    // Valid place of birth
    expect(fn () => $individualDetail->setPlaceOfBirth('New York'))
        ->not->toThrow(ValidationException::class);

    // Place of birth with special characters
    expect(fn () => $individualDetail->setPlaceOfBirth('New@York'))
        ->toThrow(ValidationException::class, 'Place of birth must be alphanumeric');

    // Null place of birth is valid
    expect(fn () => $individualDetail->setPlaceOfBirth(null))
        ->not->toThrow(ValidationException::class);
});

test('validates date of birth', function () {
    $individualDetail = new IndividualDetailData('John');

    // Valid date of birth
    expect(fn () => $individualDetail->setDateOfBirth('1990-01-01'))
        ->not->toThrow(ValidationException::class);

    // Invalid date format
    expect(fn () => $individualDetail->setDateOfBirth('1990/01/01'))
        ->toThrow(ValidationException::class, 'Date of birth must be in YYYY-MM-DD format');

    // Invalid date
    expect(fn () => $individualDetail->setDateOfBirth('2024-13-45'))
        ->toThrow(ValidationException::class, 'Invalid date of birth');

    // Null date of birth is valid
    expect(fn () => $individualDetail->setDateOfBirth(null))
        ->not->toThrow(ValidationException::class);
});

test('validates gender', function () {
    $individualDetail = new IndividualDetailData('John');

    // Valid genders
    foreach (['MALE', 'FEMALE', 'OTHER'] as $gender) {
        expect(fn () => $individualDetail->setGender($gender))
            ->not->toThrow(ValidationException::class);
    }

    // Invalid gender
    expect(fn () => $individualDetail->setGender('INVALID'))
        ->toThrow(ValidationException::class, 'Gender must be one of: MALE, FEMALE, OTHER');

    // Null gender is valid
    expect(fn () => $individualDetail->setGender(null))
        ->not->toThrow(ValidationException::class);
});

test('setter methods update properties and return self', function () {
    $individualDetail = new IndividualDetailData('John');

    $result = $individualDetail
        ->setGivenNames('Jane')
        ->setSurname('Doe')
        ->setNationality('US')
        ->setPlaceOfBirth('New York')
        ->setDateOfBirth('1990-01-01')
        ->setGender('FEMALE');

    expect($result)
        ->toBeInstanceOf(IndividualDetailData::class)
        ->givenNames->toBe('Jane')
        ->surname->toBe('Doe')
        ->nationality->toBe('US')
        ->placeOfBirth->toBe('New York')
        ->dateOfBirth->toBe('1990-01-01')
        ->gender->toBe('FEMALE');
});

test('nullable fields accept null values', function () {
    $individualDetail = new IndividualDetailData('John');

    expect(fn () => $individualDetail->setSurname(null))->not->toThrow(ValidationException::class);
    expect(fn () => $individualDetail->setNationality(null))->not->toThrow(ValidationException::class);
    expect(fn () => $individualDetail->setPlaceOfBirth(null))->not->toThrow(ValidationException::class);
    expect(fn () => $individualDetail->setDateOfBirth(null))->not->toThrow(ValidationException::class);
    expect(fn () => $individualDetail->setGender(null))->not->toThrow(ValidationException::class);

    expect($individualDetail)
        ->surname->toBeNull()
        ->nationality->toBeNull()
        ->placeOfBirth->toBeNull()
        ->dateOfBirth->toBeNull()
        ->gender->toBeNull();
});
