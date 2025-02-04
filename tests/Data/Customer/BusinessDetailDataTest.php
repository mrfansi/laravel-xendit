<?php

use Mrfansi\LaravelXendit\Data\Customer\BusinessDetailData;
use Mrfansi\LaravelXendit\Enums\BusinessType;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;

test('it can be instantiated with only required parameters using string business type', function () {
    $businessDetail = new BusinessDetailData(
        businessName: 'Test Company',
        businessType: BusinessType::CORPORATION->value
    );

    expect($businessDetail)
        ->toBeInstanceOf(BusinessDetailData::class)
        ->businessName->toBe('Test Company')
        ->businessType->toBe(BusinessType::CORPORATION->value)
        ->tradingName->toBeNull()
        ->natureOfBusiness->toBeNull()
        ->businessDomicile->toBeNull()
        ->dateOfRegistration->toBeNull();
});

test('it can be instantiated with only required parameters using enum business type', function () {
    $businessDetail = new BusinessDetailData(
        businessName: 'Test Company',
        businessType: BusinessType::CORPORATION
    );

    expect($businessDetail)
        ->toBeInstanceOf(BusinessDetailData::class)
        ->businessName->toBe('Test Company')
        ->businessType->toBe(BusinessType::CORPORATION)
        ->tradingName->toBeNull()
        ->natureOfBusiness->toBeNull()
        ->businessDomicile->toBeNull()
        ->dateOfRegistration->toBeNull();
});

test('it can be instantiated with all parameters using string business type', function () {
    $businessDetail = new BusinessDetailData(
        businessName: 'Test Company',
        businessType: BusinessType::CORPORATION->value,
        tradingName: 'Trading Co',
        natureOfBusiness: 'Technology',
        businessDomicile: 'ID',
        dateOfRegistration: '2024-01-01'
    );

    expect($businessDetail)
        ->toBeInstanceOf(BusinessDetailData::class)
        ->businessName->toBe('Test Company')
        ->businessType->toBe(BusinessType::CORPORATION->value)
        ->tradingName->toBe('Trading Co')
        ->natureOfBusiness->toBe('Technology')
        ->businessDomicile->toBe('ID')
        ->dateOfRegistration->toBe('2024-01-01');
});

test('it can be instantiated with all parameters using enum business type', function () {
    $businessDetail = new BusinessDetailData(
        businessName: 'Test Company',
        businessType: BusinessType::CORPORATION,
        tradingName: 'Trading Co',
        natureOfBusiness: 'Technology',
        businessDomicile: 'ID',
        dateOfRegistration: '2024-01-01'
    );

    expect($businessDetail)
        ->toBeInstanceOf(BusinessDetailData::class)
        ->businessName->toBe('Test Company')
        ->businessType->toBe(BusinessType::CORPORATION)
        ->tradingName->toBe('Trading Co')
        ->natureOfBusiness->toBe('Technology')
        ->businessDomicile->toBe('ID')
        ->dateOfRegistration->toBe('2024-01-01');
});

test('toArray returns only required fields when optional fields are null', function () {
    $businessDetail = new BusinessDetailData(
        businessName: 'Test Company',
        businessType: BusinessType::CORPORATION
    );

    $array = $businessDetail->toArray();

    expect($array)
        ->toBe([
            'business_name' => 'Test Company',
            'business_type' => BusinessType::CORPORATION->value,
        ])
        ->not->toHaveKeys([
            'trading_name',
            'nature_of_business',
            'business_domicile',
            'date_of_registration',
        ]);
});

test('toArray includes all non-null fields', function () {
    $businessDetail = new BusinessDetailData(
        businessName: 'Test Company',
        businessType: BusinessType::CORPORATION,
        tradingName: 'Trading Co',
        natureOfBusiness: 'Technology',
        businessDomicile: 'ID',
        dateOfRegistration: '2024-01-01'
    );

    expect($businessDetail->toArray())->toBe([
        'business_name' => 'Test Company',
        'business_type' => BusinessType::CORPORATION->value,
        'trading_name' => 'Trading Co',
        'nature_of_business' => 'Technology',
        'business_domicile' => 'ID',
        'date_of_registration' => '2024-01-01',
    ]);
});

test('validates business name', function () {
    // Valid business name
    expect(fn () => new BusinessDetailData('Test Company', BusinessType::CORPORATION))
        ->not->toThrow(ValidationException::class);

    // Empty business name
    expect(fn () => new BusinessDetailData('', BusinessType::CORPORATION))
        ->toThrow(ValidationException::class, 'Business name is required');

    // Business name with special characters
    expect(fn () => new BusinessDetailData('Test@Company', BusinessType::CORPORATION))
        ->toThrow(ValidationException::class, 'Business name must be alphanumeric');

    // Business name too long
    expect(fn () => new BusinessDetailData(str_repeat('a', 256), BusinessType::CORPORATION))
        ->toThrow(ValidationException::class, 'Business name must not exceed 255 characters');
});

test('validates business type with enum and string values', function () {
    // Valid business types using enum
    foreach (BusinessType::cases() as $type) {
        expect(fn () => new BusinessDetailData('Test Company', $type))
            ->not->toThrow(ValidationException::class);
    }

    // Valid business types using string
    foreach (BusinessType::cases() as $type) {
        expect(fn () => new BusinessDetailData('Test Company', $type->value))
            ->not->toThrow(ValidationException::class);
    }

    // Invalid business type
    $expectedMessage = 'Business type must be one of: '.implode(', ', array_column(BusinessType::cases(), 'value'));
    expect(fn () => new BusinessDetailData('Test Company', 'INVALID_TYPE'))
        ->toThrow(ValidationException::class, $expectedMessage);
});

test('validates optional fields', function () {
    $businessDetail = new BusinessDetailData(
        businessName: 'Test Company',
        businessType: BusinessType::CORPORATION
    );

    // Trading name validation
    expect(fn () => $businessDetail->setTradingName(str_repeat('a', 256)))
        ->toThrow(ValidationException::class, 'Trading name must not exceed 255 characters');
    expect(fn () => $businessDetail->setTradingName('Trading@Name'))
        ->toThrow(ValidationException::class, 'Trading name must be alphanumeric');

    // Nature of business validation
    expect(fn () => $businessDetail->setNatureOfBusiness(str_repeat('a', 256)))
        ->toThrow(ValidationException::class, 'Nature of business must not exceed 255 characters');
    expect(fn () => $businessDetail->setNatureOfBusiness('Nature@Business'))
        ->toThrow(ValidationException::class, 'Nature of business must be alphanumeric');

    // Business domicile validation
    expect(fn () => $businessDetail->setBusinessDomicile('USA'))
        ->toThrow(ValidationException::class, 'Business domicile must be a valid ISO 3166-2 code (2 letters)');

    // Date of registration validation
    expect(fn () => $businessDetail->setDateOfRegistration('2024/01/01'))
        ->toThrow(ValidationException::class, 'Date of registration must be in YYYY-MM-DD format');
});

test('setter methods update properties and return self with string business type', function () {
    $businessDetail = new BusinessDetailData(
        businessName: 'Test Company',
        businessType: BusinessType::CORPORATION->value
    );

    $result = $businessDetail
        ->setBusinessName('New Company')
        ->setBusinessType(BusinessType::PARTNERSHIP->value)
        ->setTradingName('New Trading')
        ->setNatureOfBusiness('New Nature')
        ->setBusinessDomicile('SG')
        ->setDateOfRegistration('2024-02-01');

    expect($result)
        ->toBeInstanceOf(BusinessDetailData::class)
        ->businessName->toBe('New Company')
        ->businessType->toBe(BusinessType::PARTNERSHIP->value)
        ->tradingName->toBe('New Trading')
        ->natureOfBusiness->toBe('New Nature')
        ->businessDomicile->toBe('SG')
        ->dateOfRegistration->toBe('2024-02-01');
});

test('setter methods update properties and return self with enum business type', function () {
    $businessDetail = new BusinessDetailData(
        businessName: 'Test Company',
        businessType: BusinessType::CORPORATION
    );

    $result = $businessDetail
        ->setBusinessName('New Company')
        ->setBusinessType(BusinessType::PARTNERSHIP)
        ->setTradingName('New Trading')
        ->setNatureOfBusiness('New Nature')
        ->setBusinessDomicile('SG')
        ->setDateOfRegistration('2024-02-01');

    expect($result)
        ->toBeInstanceOf(BusinessDetailData::class)
        ->businessName->toBe('New Company')
        ->businessType->toBe(BusinessType::PARTNERSHIP)
        ->tradingName->toBe('New Trading')
        ->natureOfBusiness->toBe('New Nature')
        ->businessDomicile->toBe('SG')
        ->dateOfRegistration->toBe('2024-02-01');
});

test('nullable fields accept null values', function () {
    $businessDetail = new BusinessDetailData(
        businessName: 'Test Company',
        businessType: BusinessType::CORPORATION
    );

    expect(fn () => $businessDetail->setTradingName(null))->not->toThrow(ValidationException::class);
    expect(fn () => $businessDetail->setNatureOfBusiness(null))->not->toThrow(ValidationException::class);
    expect(fn () => $businessDetail->setBusinessDomicile(null))->not->toThrow(ValidationException::class);
    expect(fn () => $businessDetail->setDateOfRegistration(null))->not->toThrow(ValidationException::class);

    expect($businessDetail)
        ->tradingName->toBeNull()
        ->natureOfBusiness->toBeNull()
        ->businessDomicile->toBeNull()
        ->dateOfRegistration->toBeNull();
});
