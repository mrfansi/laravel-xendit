<?php

namespace Mrfansi\Xendit\Data;

use DateTimeInterface;

/**
 * Class InvoiceData
 *
 * Represents complete invoice data including all details and status
 */
class InvoiceData extends AbstractDataTransferObject
{
    /**
     * @param  string|null  $id  Invoice ID from Xendit
     * @param  string|null  $externalId  External ID for the invoice
     * @param  string|null  $userId  User ID associated with the invoice
     * @param  string|null  $payerEmail  Email of the payer
     * @param  string|null  $description  Description of the invoice
     * @param  float|null  $amount  Amount of the invoice
     * @param  array<Item>|null  $items  List of items in the invoice
     * @param  array<Fee>|null  $fees  List of fees applied to the invoice
     * @param  string|null  $status  Current status of the invoice
     * @param  string|null  $merchantName  Name of the merchant
     * @param  string|null  $merchantProfilePictureUrl  URL of merchant's profile picture
     * @param  string|null  $currency  Currency code (e.g., 'IDR', 'USD')
     * @param  DateTimeInterface|null  $created  Creation timestamp
     * @param  DateTimeInterface|null  $updated  Last update timestamp
     * @param  DateTimeInterface|null  $paid  Payment timestamp
     * @param  DateTimeInterface|null  $expiry  Expiry timestamp
     * @param  string|null  $invoiceUrl  URL to view the invoice
     * @param  array<string>|null  $availablePaymentMethods  Available payment methods
     * @param  bool|null  $shouldExcludeCreditCard  Whether to exclude credit card payments
     * @param  bool|null  $shouldSendEmail  Whether to send email notification
     * @param  array<string, mixed>|null  $metadata  Additional metadata
     */
    public function __construct(
        public ?string $id = null,
        public ?string $externalId = null,
        public ?string $userId = null,
        public ?string $payerEmail = null,
        public ?string $description = null,
        public ?float $amount = null,
        public ?array $items = null,
        public ?array $fees = null,
        public ?string $status = null,
        public ?string $merchantName = null,
        public ?string $merchantProfilePictureUrl = null,
        public ?string $currency = null,
        public ?DateTimeInterface $created = null,
        public ?DateTimeInterface $updated = null,
        public ?DateTimeInterface $paid = null,
        public ?DateTimeInterface $expiry = null,
        public ?string $invoiceUrl = null,
        public ?array $availablePaymentMethods = null,
        public ?bool $shouldExcludeCreditCard = null,
        public ?bool $shouldSendEmail = null,
        public ?array $metadata = null,
    ) {}
}
