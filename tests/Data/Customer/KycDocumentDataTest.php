<?php

use Mrfansi\LaravelXendit\Data\Customer\KycDocumentData;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;

test('it can be instantiated with only required parameters', function () {
    $document = new KycDocumentData(
        country: 'ID',
        type: 'IDENTITY_CARD'
    );

    expect($document)
        ->toBeInstanceOf(KycDocumentData::class)
        ->country->toBe('ID')
        ->type->toBe('IDENTITY_CARD')
        ->subType->toBeNull()
        ->documentName->toBeNull()
        ->documentNumber->toBeNull()
        ->expiresAt->toBeNull()
        ->holderName->toBeNull()
        ->documentImages->toBeNull();
});

test('it can be instantiated with all parameters', function () {
    $document = new KycDocumentData(
        country: 'ID',
        type: 'IDENTITY_CARD',
        subType: 'NATIONAL_ID',
        documentName: 'KTP',
        documentNumber: '1234567890',
        expiresAt: '2024-12-31',
        holderName: 'John Doe',
        documentImages: ['image1.jpg', 'image2.jpg']
    );

    expect($document)
        ->toBeInstanceOf(KycDocumentData::class)
        ->country->toBe('ID')
        ->type->toBe('IDENTITY_CARD')
        ->subType->toBe('NATIONAL_ID')
        ->documentName->toBe('KTP')
        ->documentNumber->toBe('1234567890')
        ->expiresAt->toBe('2024-12-31')
        ->holderName->toBe('John Doe')
        ->documentImages->toBe(['image1.jpg', 'image2.jpg']);
});

test('toArray returns only required fields when optional fields are null', function () {
    $document = new KycDocumentData(
        country: 'ID',
        type: 'IDENTITY_CARD'
    );

    expect($document->toArray())
        ->toBe([
            'country' => 'ID',
            'type' => 'IDENTITY_CARD',
        ])
        ->not->toHaveKeys([
            'sub_type',
            'document_name',
            'document_number',
            'expires_at',
            'holder_name',
            'document_images',
        ]);
});

test('toArray includes all non-null fields', function () {
    $document = new KycDocumentData(
        country: 'ID',
        type: 'IDENTITY_CARD',
        subType: 'NATIONAL_ID',
        documentName: 'KTP',
        documentNumber: '1234567890',
        expiresAt: '2024-12-31',
        holderName: 'John Doe',
        documentImages: ['image1.jpg', 'image2.jpg']
    );

    expect($document->toArray())->toBe([
        'country' => 'ID',
        'type' => 'IDENTITY_CARD',
        'sub_type' => 'NATIONAL_ID',
        'document_name' => 'KTP',
        'document_number' => '1234567890',
        'expires_at' => '2024-12-31',
        'holder_name' => 'John Doe',
        'document_images' => ['image1.jpg', 'image2.jpg'],
    ]);
});

test('validates country', function () {
    // Valid country
    expect(fn () => new KycDocumentData('ID', 'IDENTITY_CARD'))
        ->not->toThrow(ValidationException::class);

    // Invalid country format
    expect(fn () => new KycDocumentData('USA', 'IDENTITY_CARD'))
        ->toThrow(ValidationException::class, 'Country must be a valid ISO 3166-2 code (2 letters)');
});

test('validates type', function () {
    $validTypes = [
        'BIRTH_CERTIFICATE',
        'BANK_STATEMENT',
        'DRIVING_LICENSE',
        'IDENTITY_CARD',
        'PASSPORT',
        'VISA',
        'BUSINESS_REGISTRATION',
        'BUSINESS_LICENSE',
    ];

    // Valid types
    foreach ($validTypes as $type) {
        expect(fn () => new KycDocumentData('ID', $type))
            ->not->toThrow(ValidationException::class);
    }

    // Invalid type
    expect(fn () => new KycDocumentData('ID', 'INVALID_TYPE'))
        ->toThrow(ValidationException::class, 'Type must be one of: '.implode(', ', $validTypes));
});

test('validates sub type', function () {
    $validSubTypes = [
        'NATIONAL_ID',
        'CONSULAR_ID',
        'VOTER_ID',
        'POSTAL_ID',
        'RESIDENCE_PERMIT',
        'TAX_ID',
        'STUDENT_ID',
        'MILITARY_ID',
        'MEDICAL_ID',
    ];

    // Valid sub types
    foreach ($validSubTypes as $subType) {
        expect(fn () => new KycDocumentData('ID', 'IDENTITY_CARD', $subType))
            ->not->toThrow(ValidationException::class);
    }

    // Invalid sub type
    expect(fn () => new KycDocumentData('ID', 'IDENTITY_CARD', 'INVALID_SUB_TYPE'))
        ->toThrow(ValidationException::class, 'Sub type must be one of: '.implode(', ', $validSubTypes));
});

test('validates optional fields', function () {
    $document = new KycDocumentData('ID', 'IDENTITY_CARD');

    // Document name validation
    expect(fn () => $document->setDocumentName(str_repeat('a', 256)))
        ->toThrow(ValidationException::class, 'Document name must not exceed 255 characters');
    expect(fn () => $document->setDocumentName('Document@123'))
        ->toThrow(ValidationException::class, 'Document name must be alphanumeric');

    // Document number validation
    expect(fn () => $document->setDocumentNumber(str_repeat('a', 256)))
        ->toThrow(ValidationException::class, 'Document number must not exceed 255 characters');

    // Expires at validation
    expect(fn () => $document->setExpiresAt('2024/12/31'))
        ->toThrow(ValidationException::class, 'Expiry date must be in YYYY-MM-DD format');

    // Holder name validation
    expect(fn () => $document->setHolderName(str_repeat('a', 256)))
        ->toThrow(ValidationException::class, 'Holder name must not exceed 255 characters');
    expect(fn () => $document->setHolderName('Holder@Name'))
        ->toThrow(ValidationException::class, 'Holder name must be alphanumeric');

    // Document images validation
    expect(fn () => $document->setDocumentImages([123]))
        ->toThrow(ValidationException::class, 'Document images must be an array of strings');
});

test('setter methods update properties and return self', function () {
    $document = new KycDocumentData(
        country: 'ID',
        type: 'IDENTITY_CARD'
    );

    $result = $document
        ->setCountry('SG')
        ->setType('PASSPORT')
        ->setSubType('NATIONAL_ID')
        ->setDocumentName('Passport')
        ->setDocumentNumber('1234567890')
        ->setExpiresAt('2024-12-31')
        ->setHolderName('John Doe')
        ->setDocumentImages(['image1.jpg', 'image2.jpg']);

    expect($result)
        ->toBeInstanceOf(KycDocumentData::class)
        ->country->toBe('SG')
        ->type->toBe('PASSPORT')
        ->subType->toBe('NATIONAL_ID')
        ->documentName->toBe('Passport')
        ->documentNumber->toBe('1234567890')
        ->expiresAt->toBe('2024-12-31')
        ->holderName->toBe('John Doe')
        ->documentImages->toBe(['image1.jpg', 'image2.jpg']);
});

test('nullable fields accept null values', function () {
    $document = new KycDocumentData(
        country: 'ID',
        type: 'IDENTITY_CARD',
        subType: 'NATIONAL_ID',
        documentName: 'KTP',
        documentNumber: '1234567890',
        expiresAt: '2024-12-31',
        holderName: 'John Doe',
        documentImages: ['image1.jpg']
    );

    expect(fn () => $document->setSubType(null))->not->toThrow(ValidationException::class);
    expect(fn () => $document->setDocumentName(null))->not->toThrow(ValidationException::class);
    expect(fn () => $document->setDocumentNumber(null))->not->toThrow(ValidationException::class);
    expect(fn () => $document->setExpiresAt(null))->not->toThrow(ValidationException::class);
    expect(fn () => $document->setHolderName(null))->not->toThrow(ValidationException::class);
    expect(fn () => $document->setDocumentImages(null))->not->toThrow(ValidationException::class);

    expect($document)
        ->subType->toBeNull()
        ->documentName->toBeNull()
        ->documentNumber->toBeNull()
        ->expiresAt->toBeNull()
        ->holderName->toBeNull()
        ->documentImages->toBeNull();
});
