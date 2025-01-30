<?php

namespace Mrfansi\Xendit\Data;

use DateTimeInterface;
use Mrfansi\Xendit\Data\Abstracts\AbstractDataTransferObject;

/**
 * Class InvoiceData
 *
 * Represents complete invoice data including all details and status
 */
class InvoiceData extends AbstractDataTransferObject
{
    public const MAX_EXTERNAL_ID_LENGTH = 255;

    public const MAX_METADATA_KEYS = 50;

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
     *
     * @throws \InvalidArgumentException
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
    ) {
        if ($externalId !== null) {
            if (strlen($externalId) === 0) {
                throw new \InvalidArgumentException('external_id must be between 1 and '.self::MAX_EXTERNAL_ID_LENGTH.' characters');
            }
            if (strlen($externalId) > self::MAX_EXTERNAL_ID_LENGTH) {
                throw new \InvalidArgumentException('external_id must be between 1 and '.self::MAX_EXTERNAL_ID_LENGTH.' characters');
            }
        }

        if ($merchantProfilePictureUrl !== null && ! filter_var($merchantProfilePictureUrl, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('merchant_profile_picture_url must be a valid URL');
        }

        if ($invoiceUrl !== null && ! filter_var($invoiceUrl, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('invoice_url must be a valid URL');
        }

        if ($metadata !== null) {
            if (count($metadata) > self::MAX_METADATA_KEYS) {
                throw new \InvalidArgumentException('metadata cannot have more than '.self::MAX_METADATA_KEYS.' keys');
            }

            foreach ($metadata as $key => $value) {
                if (strlen($key) > 128) {
                    throw new \InvalidArgumentException('metadata key cannot exceed 128 characters');
                }
            }
        }
    }
}
