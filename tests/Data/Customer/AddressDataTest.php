<?php

use Mrfansi\LaravelXendit\Data\Customer\AddressData;

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
        streetLine1: 'Jl. Test',
        streetLine2: 'No. 123',
        postalCode: '12345',
        category: 'home',
        isPrimary: true
    );

    $this->assertInstanceOf(AddressData::class, $address);
    $this->assertEquals('ID', $address->country);
    $this->assertEquals('DKI Jakarta', $address->provinceState);
    $this->assertEquals('Jakarta Selatan', $address->city);
    $this->assertEquals('Jl. Test', $address->streetLine1);
    $this->assertEquals('No. 123', $address->streetLine2);
    $this->assertEquals('12345', $address->postalCode);
    $this->assertEquals('home', $address->category);
    $this->assertTrue($address->isPrimary);
});

test('toArray excludes specific null values while keeping non-null values', function () {
    $address = new AddressData('ID');
    $address->setProvinceState('DKI Jakarta')
        ->setCity(null)
        ->setStreetLine1('Jl. Test')
        ->setStreetLine2(null)
        ->setPostalCode('12345')
        ->setCategory(null)
        ->setIsPrimary(true);

    $array = $address->toArray();

    $this->assertEquals([
        'country' => 'ID',
        'province_state' => 'DKI Jakarta',
        'street_line1' => 'Jl. Test',
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
        streetLine1: 'Jl. Test',
        streetLine2: 'No. 123',
        postalCode: '12345',
        category: 'home',
        isPrimary: true
    );

    $array = $address->toArray();

    $this->assertEquals([
        'country' => 'ID',
        'province_state' => 'DKI Jakarta',
        'city' => 'Jakarta Selatan',
        'street_line1' => 'Jl. Test',
        'street_line2' => 'No. 123',
        'postal_code' => '12345',
        'category' => 'home',
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
    $result = $address->setStreetLine1('Jl. Test');

    $this->assertInstanceOf(AddressData::class, $result);
    $this->assertEquals('Jl. Test', $address->streetLine1);
});

test('setStreetLine2 sets street line 2 and returns self', function () {
    $address = new AddressData('ID');
    $result = $address->setStreetLine2('No. 123');

    $this->assertInstanceOf(AddressData::class, $result);
    $this->assertEquals('No. 123', $address->streetLine2);
});

test('setPostalCode sets postal code and returns self', function () {
    $address = new AddressData('ID');
    $result = $address->setPostalCode('12345');

    $this->assertInstanceOf(AddressData::class, $result);
    $this->assertEquals('12345', $address->postalCode);
});

test('setCategory sets category and returns self', function () {
    $address = new AddressData('ID');
    $result = $address->setCategory('home');

    $this->assertInstanceOf(AddressData::class, $result);
    $this->assertEquals('home', $address->category);
});

test('setIsPrimary sets is primary and returns self', function () {
    $address = new AddressData('ID');
    $result = $address->setIsPrimary(true);

    $this->assertInstanceOf(AddressData::class, $result);
    $this->assertTrue($address->isPrimary);
});

test('nullable fields accept null values', function () {
    $address = new AddressData('ID');

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
        ->setStreetLine1('Jl. Test')
        ->setStreetLine2('No. 123')
        ->setPostalCode('12345')
        ->setCategory('home')
        ->setIsPrimary(true);

    $this->assertInstanceOf(AddressData::class, $result);
    $this->assertEquals('DKI Jakarta', $address->provinceState);
    $this->assertEquals('Jakarta Selatan', $address->city);
    $this->assertEquals('Jl. Test', $address->streetLine1);
    $this->assertEquals('No. 123', $address->streetLine2);
    $this->assertEquals('12345', $address->postalCode);
    $this->assertEquals('home', $address->category);
    $this->assertTrue($address->isPrimary);
});
