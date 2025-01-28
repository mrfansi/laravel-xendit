<?php

use Mrfansi\XenditSdk\Data\Item;

test('item can be created with required fields', function () {
    $item = new Item(
        name: 'Test Product',
        quantity: 2,
        price: 100000
    );

    expect($item)
        ->name->toBe('Test Product')
        ->quantity->toBe(2)
        ->price->toBe(100000.0)
        ->category->toBeNull()
        ->url->toBeNull();
});

test('item can be created with all fields', function () {
    $item = new Item(
        name: 'Test Product',
        quantity: 2,
        price: 100000,
        category: 'Electronics',
        url: 'https://example.com/product'
    );

    expect($item)
        ->name->toBe('Test Product')
        ->quantity->toBe(2)
        ->price->toBe(100000.0)
        ->category->toBe('Electronics')
        ->url->toBe('https://example.com/product');
});

test('item throws exception for name exceeding max length', function () {
    expect(fn () => new Item(
        name: str_repeat('a', 257), // 257 characters
        quantity: 1,
        price: 100000
    ))->toThrow(InvalidArgumentException::class, 'Item name cannot exceed 256 characters');
});

test('item throws exception for quantity exceeding max value', function () {
    expect(fn () => new Item(
        name: 'Test Product',
        quantity: 510001, // Maximum is 510000
        price: 100000
    ))->toThrow(InvalidArgumentException::class, 'Item quantity cannot exceed 510000');
});

test('item throws exception for invalid URL', function () {
    expect(fn () => new Item(
        name: 'Test Product',
        quantity: 1,
        price: 100000,
        url: 'invalid-url'
    ))->toThrow(InvalidArgumentException::class, 'Item URL must be a valid HTTP or HTTPS URL');
});

test('item can be converted to array', function () {
    $item = new Item(
        name: 'Test Product',
        quantity: 2,
        price: 100000,
        category: 'Electronics',
        url: 'https://example.com/product'
    );

    $array = $item->all();

    expect($array)
        ->toHaveKeys(['name', 'quantity', 'price', 'category', 'url'])
        ->and($array['name'])->toBe('Test Product')
        ->and($array['quantity'])->toBe(2)
        ->and($array['price'])->toBe(100000.0)
        ->and($array['category'])->toBe('Electronics')
        ->and($array['url'])->toBe('https://example.com/product');
});
