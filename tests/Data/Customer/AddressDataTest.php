<?php

use Mrfansi\LaravelXendit\Data\Customer\AddressData;
use Mrfansi\LaravelXendit\Enums\AddressCategory;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;

test('it can be instantiated with only required country parameter', function () {
    $address = new AddressData('ID');

    $this->assertInstanceOf(AddressData::class, $address);
    $this->assertEquals('ID', $address->country);
    $this->assertNull($address->provinceState);
    $this->assertNull($address->city);
    $this->assertNull($address->streetLine1);
    $this->assertNull($address->streetLine2);
    $this->assertNull($address->postalCode);
    $this->assertNull($address->category);
    $this->assertFalse($address->isPrimary);
});

test('it can be instantiated with all parameters', function () {
    $address = new AddressData(
        country: 'ID',
        provinceState: 'DKI Jakarta',
        city: 'Jakarta Selatan',
        streetLine1: 'Jalan Test No. 1',
        streetLine2: 'RT/RW 001/002',
        postalCode: '12345',
        category: AddressCategory::HOME,
        isPrimary: true
    );

    $this->assertInstanceOf(AddressData::class, $address);
    $this->assertEquals('ID', $address->country);
    $this->assertEquals('DKI Jakarta', $address->provinceState);
    $this->assertEquals('Jakarta Selatan', $address->city);
    $this->assertEquals('Jalan Test No. 1', $address->streetLine1);
    $this->assertEquals('RT/RW 001/002', $address->streetLine2);
    $this->assertEquals('12345', $address->postalCode);
    $this->assertEquals(AddressCategory::HOME, $address->category);
    $this->assertTrue($address->isPrimary);
});

test('toArray excludes specific null values while keeping non-null values', function () {
    $address = new AddressData('ID');
    $address->setProvinceState('DKI Jakarta')
        ->setCity(null)
        ->setStreetLine1('Jalan Test No. 1')
        ->setStreetLine2(null)
        ->setPostalCode('12345')
        ->setCategory(null)
        ->setIsPrimary(true);

    $array = $address->toArray();

    $this->assertEquals([
        'country' => 'ID',
        'province_state' => 'DKI Jakarta',
        'street_line1' => 'Jalan Test No. 1',
        'postal_code' => '12345',
        'is_primary' => true,
    ], $array);

    $this->assertArrayNotHasKey('city', $array);
    $this->assertArrayNotHasKey('street_line2', $array);
    $this->assertArrayNotHasKey('category', $array);
});

test('toArray returns only country when other fields are null', function () {
    $address = new AddressData('ID');

    $array = $address->toArray();

    $this->assertEquals(['country' => 'ID'], $array);
    $this->assertArrayNotHasKey('province_state', $array);
    $this->assertArrayNotHasKey('city', $array);
    $this->assertArrayNotHasKey('street_line1', $array);
    $this->assertArrayNotHasKey('street_line2', $array);
    $this->assertArrayNotHasKey('postal_code', $array);
    $this->assertArrayNotHasKey('category', $array);
    $this->assertArrayNotHasKey('is_primary', $array);
});

test('toArray includes only non-null values', function () {
    $address = new AddressData(
        country: 'ID',
        provinceState: 'DKI Jakarta',
        city: 'Jakarta Selatan',
        streetLine1: 'Jalan Test No. 1',
        streetLine2: 'RT/RW 001/002',
        postalCode: '12345',
        category: AddressCategory::HOME,
        isPrimary: true
    );

    $array = $address->toArray();

    $this->assertEquals([
        'country' => 'ID',
        'province_state' => 'DKI Jakarta',
        'city' => 'Jakarta Selatan',
        'street_line1' => 'Jalan Test No. 1',
        'street_line2' => 'RT/RW 001/002',
        'postal_code' => '12345',
        'category' => 'HOME',
        'is_primary' => true,
    ], $array);
});

test('setCountry sets country and returns self', function () {
    $address = new AddressData('ID');
    $result = $address->setCountry('US');

    $this->assertInstanceOf(AddressData::class, $result);
    $this->assertEquals('US', $address->country);
});

test('setProvinceState sets province/state and returns self', function () {
    $address = new AddressData('ID');
    $result = $address->setProvinceState('DKI Jakarta');

    $this->assertInstanceOf(AddressData::class, $result);
    $this->assertEquals('DKI Jakarta', $address->provinceState);
});

test('setCity sets city and returns self', function () {
    $address = new AddressData('ID');
    $result = $address->setCity('Jakarta Selatan');

    $this->assertInstanceOf(AddressData::class, $result);
    $this->assertEquals('Jakarta Selatan', $address->city);
});

test('setStreetLine1 sets street line 1 and returns self', function () {
    $address = new AddressData('ID');
    $result = $address->setStreetLine1('Jalan Test No. 1');

    $this->assertInstanceOf(AddressData::class, $result);
    $this->assertEquals('Jalan Test No. 1', $address->streetLine1);
});

test('setStreetLine2 sets street line 2 and returns self', function () {
    $address = new AddressData('ID');
    $result = $address->setStreetLine2('RT/RW 001/002');

    $this->assertInstanceOf(AddressData::class, $result);
    $this->assertEquals('RT/RW 001/002', $address->streetLine2);
});

test('setPostalCode sets postal code and returns self', function () {
    $address = new AddressData('ID');
    $result = $address->setPostalCode('12345');

    $this->assertInstanceOf(AddressData::class, $result);
    $this->assertEquals('12345', $address->postalCode);
});

test('setCategory sets category and returns self', function () {
    $address = new AddressData('ID');
    $result = $address->setCategory(AddressCategory::HOME->value);

    // Test with string value
    $this->assertInstanceOf(AddressData::class, $result);
    $this->assertEquals(AddressCategory::HOME->value, $address->category);

    // Test with enum value
    $result = $address->setCategory(AddressCategory::WORK);
    $this->assertEquals(AddressCategory::WORK, $address->category);
    $this->assertEquals('WORK', $address->toArray()['category']);
});

test('setIsPrimary sets is primary and returns self', function () {
    $address = new AddressData('ID');
    $result = $address->setIsPrimary(true);

    $this->assertInstanceOf(AddressData::class, $result);
    $this->assertTrue($address->isPrimary);
});

test('validates ISO 3166-2 country code', function () {
    // Valid country codes
    expect(fn () => new AddressData('ID'))->not->toThrow(ValidationException::class);
    expect(fn () => new AddressData('US'))->not->toThrow(ValidationException::class);

    // Invalid country codes
    expect(fn () => new AddressData('USA'))->toThrow(ValidationException::class, 'Country must be a valid ISO 3166-2 code (2 letters)');
    expect(fn () => new AddressData('1'))->toThrow(ValidationException::class, 'Country must be a valid ISO 3166-2 code (2 letters)');
    expect(fn () => new AddressData(''))->toThrow(ValidationException::class, 'Country must be a valid ISO 3166-2 code (2 letters)');
});

test('validates maximum length of 255 characters', function () {
    $address = new AddressData('ID');
    $longString = str_repeat('a', 256);
    $validString = str_repeat('a', 255);

    // Test each field with max length
    expect(fn () => $address->setProvinceState($longString))->toThrow(ValidationException::class, 'Province/state must not exceed 255 characters');
    expect(fn () => $address->setCity($longString))->toThrow(ValidationException::class, 'City must not exceed 255 characters');
    expect(fn () => $address->setStreetLine1($longString))->toThrow(ValidationException::class, 'Street line 1 must not exceed 255 characters');
    expect(fn () => $address->setStreetLine2($longString))->toThrow(ValidationException::class, 'Street line 2 must not exceed 255 characters');
    expect(fn () => $address->setPostalCode($longString))->toThrow(ValidationException::class, 'Postal code must not exceed 255 characters');

    // Verify valid lengths are accepted
    expect(fn () => $address->setProvinceState($validString))->not->toThrow(ValidationException::class);
    expect(fn () => $address->setCity($validString))->not->toThrow(ValidationException::class);
    expect(fn () => $address->setStreetLine1($validString))->not->toThrow(ValidationException::class);
    expect(fn () => $address->setStreetLine2($validString))->not->toThrow(ValidationException::class);
    expect(fn () => $address->setPostalCode($validString))->not->toThrow(ValidationException::class);
});

test('validates allowed characters', function () {
    $address = new AddressData('ID');
    $invalidString = 'Test@123#';
    $validString = 'Jalan Test No. 1, RT/RW 001/002';

    // Test each field with invalid characters
    expect(fn () => $address->setProvinceState($invalidString))->toThrow(ValidationException::class, 'Province/state must be alphanumeric');
    expect(fn () => $address->setCity($invalidString))->toThrow(ValidationException::class, 'City must be alphanumeric');
    expect(fn () => $address->setStreetLine1($invalidString))->toThrow(ValidationException::class, 'Street line 1 must be alphanumeric');
    expect(fn () => $address->setStreetLine2($invalidString))->toThrow(ValidationException::class, 'Street line 2 must be alphanumeric');
    expect(fn () => $address->setPostalCode($invalidString))->toThrow(ValidationException::class, 'Postal code must be alphanumeric');

    // Verify valid strings are accepted
    expect(fn () => $address->setProvinceState($validString))->not->toThrow(ValidationException::class);
    expect(fn () => $address->setCity($validString))->not->toThrow(ValidationException::class);
    expect(fn () => $address->setStreetLine1($validString))->not->toThrow(ValidationException::class);
    expect(fn () => $address->setStreetLine2($validString))->not->toThrow(ValidationException::class);
    expect(fn () => $address->setPostalCode($validString))->not->toThrow(ValidationException::class);
});

test('validates category values', function () {
    $address = new AddressData('ID');

    // Valid categories
    // Test with string values
    expect(fn () => $address->setCategory(AddressCategory::HOME->value))->not->toThrow(ValidationException::class);
    expect(fn () => $address->setCategory(AddressCategory::WORK->value))->not->toThrow(ValidationException::class);
    expect(fn () => $address->setCategory(AddressCategory::PROVINCIAL->value))->not->toThrow(ValidationException::class);
    // Test with enum values
    expect(fn () => $address->setCategory(AddressCategory::HOME))->not->toThrow(ValidationException::class);
    expect(fn () => $address->setCategory(AddressCategory::WORK))->not->toThrow(ValidationException::class);
    expect(fn () => $address->setCategory(null))->not->toThrow(ValidationException::class);

    // Invalid categories
    expect(fn () => $address->setCategory('INVALID'))->toThrow(ValidationException::class, 'Category must be one of: HOME, WORK, PROVINCIAL');
    expect(fn () => $address->setCategory('Office'))->toThrow(ValidationException::class, 'Category must be one of: HOME, WORK, PROVINCIAL');
});

test('nullable fields accept null values', function () {
    $address = new AddressData('ID');

    expect(fn () => $address->setProvinceState(null))->not->toThrow(ValidationException::class);
    expect(fn () => $address->setCity(null))->not->toThrow(ValidationException::class);
    expect(fn () => $address->setStreetLine1(null))->not->toThrow(ValidationException::class);
    expect(fn () => $address->setStreetLine2(null))->not->toThrow(ValidationException::class);
    expect(fn () => $address->setPostalCode(null))->not->toThrow(ValidationException::class);
    expect(fn () => $address->setCategory(null))->not->toThrow(ValidationException::class);

    $this->assertNull($address->setProvinceState(null)->provinceState);
    $this->assertNull($address->setCity(null)->city);
    $this->assertNull($address->setStreetLine1(null)->streetLine1);
    $this->assertNull($address->setStreetLine2(null)->streetLine2);
    $this->assertNull($address->setPostalCode(null)->postalCode);
    $this->assertNull($address->setCategory(null)->category);
});

test('method chaining works with multiple setters', function () {
    $address = new AddressData('ID');

    $result = $address
        ->setProvinceState('DKI Jakarta')
        ->setCity('Jakarta Selatan')
        ->setStreetLine1('Jalan Test No. 1')
        ->setStreetLine2('RT/RW 001/002')
        ->setPostalCode('12345')
        ->setCategory(AddressCategory::HOME)
        ->setIsPrimary(true);

    $this->assertInstanceOf(AddressData::class, $result);
    $this->assertEquals('DKI Jakarta', $address->provinceState);
    $this->assertEquals('Jakarta Selatan', $address->city);
    $this->assertEquals('Jalan Test No. 1', $address->streetLine1);
    $this->assertEquals('RT/RW 001/002', $address->streetLine2);
    $this->assertEquals('12345', $address->postalCode);
    $this->assertEquals(AddressCategory::HOME, $address->category);
    $this->assertTrue($address->isPrimary);
});
