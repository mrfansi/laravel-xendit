<?php

use Mrfansi\LaravelXendit\Data\Customer\BusinessDetailData;
use Mrfansi\LaravelXendit\Data\Customer\CustomerData;
use Mrfansi\LaravelXendit\Data\Customer\IndividualDetailData;
use Mrfansi\LaravelXendit\Enums\BusinessType;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;

test('it can be instantiated with only required parameters for individual type', function () {
    $individualDetail = new IndividualDetailData(
        givenNames: 'John'
    );

    $customer = new CustomerData(
        referenceId: 'REF123',
        type: 'INDIVIDUAL',
        individualDetail: $individualDetail
    );

    expect($customer)
        ->toBeInstanceOf(CustomerData::class)
        ->referenceId->toBe('REF123')
        ->type->toBe('INDIVIDUAL')
        ->individualDetail->toBeInstanceOf(IndividualDetailData::class)
        ->businessDetail->toBeNull()
        ->mobileNumber->toBeNull()
        ->phoneNumber->toBeNull()
        ->hashedPhoneNumber->toBeNull()
        ->email->toBeNull()
        ->addresses->toBeNull()
        ->identityAccounts->toBeNull()
        ->kycDocuments->toBeNull()
        ->description->toBeNull()
        ->dateOfRegistration->toBeNull()
        ->domicileOfRegistration->toBeNull()
        ->metadata->toBeNull();
});

test('it can be instantiated with only required parameters for business type', function () {
    $businessDetail = new BusinessDetailData(
        businessName: 'Test Company',
        businessType: BusinessType::CORPORATION
    );

    $customer = new CustomerData(
        referenceId: 'REF123',
        type: 'BUSINESS',
        businessDetail: $businessDetail
    );

    expect($customer)
        ->toBeInstanceOf(CustomerData::class)
        ->referenceId->toBe('REF123')
        ->type->toBe('BUSINESS')
        ->businessDetail->toBeInstanceOf(BusinessDetailData::class)
        ->individualDetail->toBeNull()
        ->mobileNumber->toBeNull()
        ->phoneNumber->toBeNull()
        ->hashedPhoneNumber->toBeNull()
        ->email->toBeNull()
        ->addresses->toBeNull()
        ->identityAccounts->toBeNull()
        ->kycDocuments->toBeNull()
        ->description->toBeNull()
        ->dateOfRegistration->toBeNull()
        ->domicileOfRegistration->toBeNull()
        ->metadata->toBeNull();
});

test('it can be instantiated with all parameters', function () {
    $individualDetail = new IndividualDetailData(
        givenNames: 'John'
    );

    $customer = new CustomerData(
        referenceId: 'REF123',
        type: 'INDIVIDUAL',
        individualDetail: $individualDetail,
        mobileNumber: '+6281234567890',
        phoneNumber: '+6281234567891',
        hashedPhoneNumber: 'hashed123',
        email: 'john@example.com',
        addresses: [],
        identityAccounts: [],
        kycDocuments: [],
        description: 'Test customer',
        dateOfRegistration: '2024-01-01',
        domicileOfRegistration: 'ID',
        metadata: ['key' => 'value']
    );

    expect($customer)
        ->toBeInstanceOf(CustomerData::class)
        ->referenceId->toBe('REF123')
        ->type->toBe('INDIVIDUAL')
        ->individualDetail->toBeInstanceOf(IndividualDetailData::class)
        ->mobileNumber->toBe('+6281234567890')
        ->phoneNumber->toBe('+6281234567891')
        ->hashedPhoneNumber->toBe('hashed123')
        ->email->toBe('john@example.com')
        ->addresses->toBe([])
        ->identityAccounts->toBe([])
        ->kycDocuments->toBe([])
        ->description->toBe('Test customer')
        ->dateOfRegistration->toBe('2024-01-01')
        ->domicileOfRegistration->toBe('ID')
        ->metadata->toBe(['key' => 'value']);
});

test('toArray returns only required fields when optional fields are null', function () {
    $individualDetail = new IndividualDetailData(
        givenNames: 'John'
    );

    $customer = new CustomerData(
        referenceId: 'REF123',
        type: 'INDIVIDUAL',
        individualDetail: $individualDetail
    );

    $array = $customer->toArray();

    expect($array)
        ->toBe([
            'reference_id' => 'REF123',
            'type' => 'INDIVIDUAL',
            'individual_detail' => $individualDetail->toArray(),
        ])
        ->not->toHaveKeys([
            'business_detail',
            'mobile_number',
            'phone_number',
            'hashed_phone_number',
            'email',
            'addresses',
            'identity_accounts',
            'kyc_documents',
            'description',
            'date_of_registration',
            'domicile_of_registration',
            'metadata',
        ]);
});

test('toArray includes all non-null fields', function () {
    $individualDetail = new IndividualDetailData(
        givenNames: 'John'
    );

    $customer = new CustomerData(
        referenceId: 'REF123',
        type: 'INDIVIDUAL',
        individualDetail: $individualDetail,
        mobileNumber: '+6281234567890',
        email: 'john@example.com',
        description: 'Test customer'
    );

    expect($customer->toArray())->toBe([
        'reference_id' => 'REF123',
        'type' => 'INDIVIDUAL',
        'individual_detail' => $individualDetail->toArray(),
        'mobile_number' => '+6281234567890',
        'email' => 'john@example.com',
        'description' => 'Test customer',
    ]);
});

test('validates reference id', function () {
    $individualDetail = new IndividualDetailData(
        givenNames: 'John'
    );

    // Valid reference id
    expect(fn () => new CustomerData('REF123', 'INDIVIDUAL', $individualDetail))
        ->not->toThrow(ValidationException::class);

    // Empty reference id
    expect(fn () => new CustomerData('', 'INDIVIDUAL', $individualDetail))
        ->toThrow(ValidationException::class, 'Reference ID is required');

    // Reference id with special characters
    expect(fn () => new CustomerData('REF@123', 'INDIVIDUAL', $individualDetail))
        ->toThrow(ValidationException::class, 'Reference ID must be alphanumeric');

    // Reference id too long
    expect(fn () => new CustomerData(str_repeat('a', 256), 'INDIVIDUAL', $individualDetail))
        ->toThrow(ValidationException::class, 'Reference ID must not exceed 255 characters');
});

test('validates type', function () {
    $individualDetail = new IndividualDetailData(
        givenNames: 'John'
    );

    // Valid types
    expect(fn () => new CustomerData('REF123', 'INDIVIDUAL', $individualDetail))
        ->not->toThrow(ValidationException::class);
    expect(fn () => new CustomerData('REF123', 'BUSINESS', null, new BusinessDetailData('Test Company', BusinessType::CORPORATION)))
        ->not->toThrow(ValidationException::class);

    // Invalid type
    expect(fn () => new CustomerData('REF123', 'INVALID', $individualDetail))
        ->toThrow(ValidationException::class, 'Type must be one of: INDIVIDUAL, BUSINESS');
});

test('validates required details based on type', function () {
    // Missing individual detail
    expect(fn () => new CustomerData('REF123', 'INDIVIDUAL'))
        ->toThrow(ValidationException::class, 'Individual detail is required when type is INDIVIDUAL');

    // Missing business detail
    expect(fn () => new CustomerData('REF123', 'BUSINESS'))
        ->toThrow(ValidationException::class, 'Business detail is required when type is BUSINESS');
});

test('validates optional fields', function () {
    $customer = new CustomerData(
        'REF123',
        'INDIVIDUAL',
        new IndividualDetailData('John')
    );

    // Mobile number validation
    expect(fn () => $customer->setMobileNumber('invalid'))
        ->toThrow(ValidationException::class, 'Mobile number must be in E.164 format');
    expect(fn () => $customer->setMobileNumber(str_repeat('1', 51)))
        ->toThrow(ValidationException::class, 'Mobile number must not exceed 50 characters');

    // Phone number validation
    expect(fn () => $customer->setPhoneNumber('invalid'))
        ->toThrow(ValidationException::class, 'Phone number must be in E.164 format');
    expect(fn () => $customer->setPhoneNumber(str_repeat('1', 51)))
        ->toThrow(ValidationException::class, 'Phone number must not exceed 50 characters');

    // Email validation
    expect(fn () => $customer->setEmail('invalid-email'))
        ->toThrow(ValidationException::class, 'Email must be a valid email address');
    expect(fn () => $customer->setEmail(str_repeat('a', 45).'@test.com'))
        ->toThrow(ValidationException::class, 'Email must not exceed 50 characters');

    // Description validation
    expect(fn () => $customer->setDescription(str_repeat('a', 501)))
        ->toThrow(ValidationException::class, 'Description must not exceed 500 characters');
    expect(fn () => $customer->setDescription('Description@123'))
        ->toThrow(ValidationException::class, 'Description must be alphanumeric');

    // Date of registration validation
    expect(fn () => $customer->setDateOfRegistration('2024/01/01'))
        ->toThrow(ValidationException::class, 'Date of registration must be in YYYY-MM-DD format');

    // Domicile of registration validation
    expect(fn () => $customer->setDomicileOfRegistration('USA'))
        ->toThrow(ValidationException::class, 'Domicile of registration must be a valid ISO 3166-2 code (2 letters)');
});

test('setter methods update properties and return self', function () {
    $customer = new CustomerData(
        'REF123',
        'INDIVIDUAL',
        new IndividualDetailData('John')
    );

    $result = $customer
        ->setReferenceId('REF456')
        ->setType('BUSINESS')
        ->setBusinessDetail(new BusinessDetailData('Test Company', BusinessType::CORPORATION))
        ->setMobileNumber('+6281234567890')
        ->setPhoneNumber('+6281234567891')
        ->setHashedPhoneNumber('hashed123')
        ->setEmail('john@example.com')
        ->setAddresses([])
        ->setIdentityAccounts([])
        ->setKycDocuments([])
        ->setDescription('Test customer')
        ->setDateOfRegistration('2024-01-01')
        ->setDomicileOfRegistration('ID')
        ->setMetadata(['key' => 'value']);

    expect($result)
        ->toBeInstanceOf(CustomerData::class)
        ->referenceId->toBe('REF456')
        ->type->toBe('BUSINESS')
        ->businessDetail->toBeInstanceOf(BusinessDetailData::class)
        ->mobileNumber->toBe('+6281234567890')
        ->phoneNumber->toBe('+6281234567891')
        ->hashedPhoneNumber->toBe('hashed123')
        ->email->toBe('john@example.com')
        ->addresses->toBe([])
        ->identityAccounts->toBe([])
        ->kycDocuments->toBe([])
        ->description->toBe('Test customer')
        ->dateOfRegistration->toBe('2024-01-01')
        ->domicileOfRegistration->toBe('ID')
        ->metadata->toBe(['key' => 'value']);
});

test('nullable fields accept null values', function () {
    $customer = new CustomerData(
        'REF123',
        'INDIVIDUAL',
        new IndividualDetailData('John')
    );

    expect(fn () => $customer->setMobileNumber(null))->not->toThrow(ValidationException::class);
    expect(fn () => $customer->setPhoneNumber(null))->not->toThrow(ValidationException::class);
    expect(fn () => $customer->setHashedPhoneNumber(null))->not->toThrow(ValidationException::class);
    expect(fn () => $customer->setEmail(null))->not->toThrow(ValidationException::class);
    expect(fn () => $customer->setAddresses(null))->not->toThrow(ValidationException::class);
    expect(fn () => $customer->setIdentityAccounts(null))->not->toThrow(ValidationException::class);
    expect(fn () => $customer->setKycDocuments(null))->not->toThrow(ValidationException::class);
    expect(fn () => $customer->setDescription(null))->not->toThrow(ValidationException::class);
    expect(fn () => $customer->setDateOfRegistration(null))->not->toThrow(ValidationException::class);
    expect(fn () => $customer->setDomicileOfRegistration(null))->not->toThrow(ValidationException::class);
    expect(fn () => $customer->setMetadata(null))->not->toThrow(ValidationException::class);

    expect($customer)
        ->mobileNumber->toBeNull()
        ->phoneNumber->toBeNull()
        ->hashedPhoneNumber->toBeNull()
        ->email->toBeNull()
        ->addresses->toBeNull()
        ->identityAccounts->toBeNull()
        ->kycDocuments->toBeNull()
        ->description->toBeNull()
        ->dateOfRegistration->toBeNull()
        ->domicileOfRegistration->toBeNull()
        ->metadata->toBeNull();
});
