<?php

namespace Mrfansi\LaravelXendit\Tests\Data\Invoice;

use Mrfansi\LaravelXendit\Data\Invoice\InvoiceCustomerData;
use Mrfansi\LaravelXendit\Data\Invoice\InvoiceData;
use Mrfansi\LaravelXendit\Enums\Currency;
use Mrfansi\LaravelXendit\Enums\Locale;
use Mrfansi\LaravelXendit\Enums\ReminderTimeUnit;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;
use PHPUnit\Framework\TestCase;

class InvoiceDataTest extends TestCase
{
    public function test_create_minimal_invoice(): void
    {
        $invoice = new InvoiceData(
            externalId: 'invoice-123',
            amount: 100000
        );

        $this->assertEquals('invoice-123', $invoice->externalId);
        $this->assertEquals(100000, $invoice->amount);
    }

    public function test_create_full_invoice(): void
    {
        $customer = new InvoiceCustomerData(
            givenNames: 'John',
            surname: 'Doe',
            email: 'john@example.com',
            mobileNumber: '+6281234567890'
        );

        $invoice = new InvoiceData(
            externalId: 'invoice-123',
            amount: 100000,
            description: 'Test Invoice',
            customer: $customer,
            customerNotificationPreference: [
                'invoice_created' => ['email', 'whatsapp'],
                'invoice_paid' => ['email'],
            ],
            invoiceDuration: 86400,
            successRedirectUrl: 'https://example.com/success',
            failureRedirectUrl: 'https://example.com/failure',
            paymentMethods: ['CREDIT_CARD', 'BCA'],
            currency: Currency::IDR,
            callbackVirtualAccountId: 'va-123',
            midLabel: 'mid-123',
            reminderTimeUnit: ReminderTimeUnit::DAYS,
            reminderTime: 1,
            locale: Locale::ENGLISH,
            items: [
                [
                    'name' => 'Item 1',
                    'quantity' => 1,
                    'price' => 50000,
                ],
                [
                    'name' => 'Item 2',
                    'quantity' => 1,
                    'price' => 50000,
                ],
            ],
            fees: [
                [
                    'type' => 'admin',
                    'value' => 1000,
                ],
            ],
            shouldAuthenticateCreditCard: true,
            channelProperties: [
                'allowed_bins' => ['123456'],
            ],
            metadata: [
                'order_id' => '123',
            ]
        );

        $this->assertEquals('invoice-123', $invoice->externalId);
        $this->assertEquals(100000, $invoice->amount);
        $this->assertEquals('Test Invoice', $invoice->description);
        $this->assertInstanceOf(InvoiceCustomerData::class, $invoice->customer);
        $this->assertEquals(['invoice_created' => ['email', 'whatsapp'], 'invoice_paid' => ['email']], $invoice->customerNotificationPreference);
        $this->assertEquals(86400, $invoice->invoiceDuration);
        $this->assertEquals('https://example.com/success', $invoice->successRedirectUrl);
        $this->assertEquals('https://example.com/failure', $invoice->failureRedirectUrl);
        $this->assertEquals(['CREDIT_CARD', 'BCA'], $invoice->paymentMethods);
        $this->assertEquals(Currency::IDR, $invoice->currency);
        $this->assertEquals('va-123', $invoice->callbackVirtualAccountId);
        $this->assertEquals('mid-123', $invoice->midLabel);
        $this->assertEquals(ReminderTimeUnit::DAYS, $invoice->reminderTimeUnit);
        $this->assertEquals(1, $invoice->reminderTime);
        $this->assertEquals(Locale::ENGLISH, $invoice->locale);
        $this->assertCount(2, $invoice->items);
        $this->assertCount(1, $invoice->fees);
        $this->assertTrue($invoice->shouldAuthenticateCreditCard);
        $this->assertEquals(['allowed_bins' => ['123456']], $invoice->channelProperties);
        $this->assertEquals(['order_id' => '123'], $invoice->metadata);
    }

    public function test_validation_fails_with_invalid_external_id(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('External ID must be between 1 and 255 characters');

        new InvoiceData(
            externalId: '',
            amount: 100000
        );
    }

    public function test_validation_fails_with_invalid_amount(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Amount must be greater than 0');

        new InvoiceData(
            externalId: 'invoice-123',
            amount: 0
        );
    }

    public function test_validation_fails_with_invalid_invoice_duration(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Invoice duration must be between 1 and 31536000 seconds');

        new InvoiceData(
            externalId: 'invoice-123',
            amount: 100000,
            invoiceDuration: 0
        );
    }

    public function test_validation_fails_with_invalid_notification_preference(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Invalid notification type: invalid_type');

        new InvoiceData(
            externalId: 'invoice-123',
            amount: 100000,
            customerNotificationPreference: [
                'invalid_type' => ['email'],
            ]
        );
    }

    public function test_validation_fails_with_invalid_notification_channel(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Invalid channel: invalid_channel');

        new InvoiceData(
            externalId: 'invoice-123',
            amount: 100000,
            customerNotificationPreference: [
                'invoice_created' => ['invalid_channel'],
            ]
        );
    }

    public function test_to_array(): void
    {
        $invoice = new InvoiceData(
            externalId: 'invoice-123',
            amount: 100000,
            description: 'Test Invoice',
            currency: Currency::IDR
        );

        $array = $invoice->toArray();

        $this->assertEquals([
            'external_id' => 'invoice-123',
            'amount' => 100000,
            'description' => 'Test Invoice',
            'currency' => 'IDR',
        ], $array);
    }

    public function test_from_array(): void
    {
        $data = [
            'external_id' => 'invoice-123',
            'amount' => 100000,
            'description' => 'Test Invoice',
            'currency' => 'IDR',
        ];

        $invoice = InvoiceData::fromArray($data);

        $this->assertEquals('invoice-123', $invoice->externalId);
        $this->assertEquals(100000, $invoice->amount);
        $this->assertEquals('Test Invoice', $invoice->description);
        $this->assertEquals(Currency::IDR, $invoice->currency);
    }
}
