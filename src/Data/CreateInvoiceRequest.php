<?php

namespace Mrfansi\Xendit\Data;

use InvalidArgumentException;
use Mrfansi\Xendit\Data\CardChannel\CardChannelProperties;
use Mrfansi\Xendit\Enums\Currency;
use Mrfansi\Xendit\Enums\Locale;
use Mrfansi\Xendit\Enums\PaymentMethod;
use Mrfansi\Xendit\Enums\ReminderTimeUnit;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class CreateInvoiceRequest extends Data
{
    public function __construct(
        /**
         * ID of your choice (typically the unique identifier of an invoice in your system)
         */
        public string $external_id,

        /**
         * Amount on the invoice (inclusive of any fees and items)
         */
        public float $amount,

        /**
         * Optional description of invoice
         */
        public ?string $description = null,

        /**
         * Customer details
         */
        public ?Customer $customer = null,

        /**
         * Notification preferences for this invoice
         */
        public ?NotificationPreference $customer_notification_preference = null,

        /**
         * Duration before invoice expires (in seconds)
         */
        public ?int $invoice_duration = null,

        /**
         * URL for successful payment redirect
         */
        public ?string $success_redirect_url = null,

        /**
         * URL for failed/expired payment redirect
         */
        public ?string $failure_redirect_url = null,

        /**
         * Available payment methods for this invoice
         *
         * @var PaymentMethod[]|null
         */
        #[DataCollectionOf(PaymentMethod::class)]
        public ?DataCollection $payment_methods = null,

        /**
         * Currency of the invoice amount
         */
        public ?Currency $currency = null,

        /**
         * Fixed Virtual Account ID for payment
         */
        public ?string $callback_virtual_account_id = null,

        /**
         * MID label for acquiring bank (credit cards)
         */
        public ?string $mid_label = null,

        /**
         * Unit for reminder time (days/hours)
         */
        public ?ReminderTimeUnit $reminder_time_unit = null,

        /**
         * When to send reminder notification
         */
        public ?int $reminder_time = null,

        /**
         * Display language
         */
        public ?Locale $locale = null,

        /**
         * Items being purchased
         *
         * @var Item[]|null
         */
        #[DataCollectionOf(Item::class)]
        public ?DataCollection $items = null,

        /**
         * Additional fees
         *
         * @var Fee[]|null
         */
        #[DataCollectionOf(Fee::class)]
        public ?DataCollection $fees = null,

        /**
         * Whether to authenticate credit card payment
         */
        public ?bool $should_authenticate_credit_card = null,

        /**
         * Channel-specific properties
         */
        public ?CardChannelProperties $channel_properties = null,

        /**
         * Additional metadata
         *
         * @var array<string, string>|null
         */
        public ?array $metadata = null,
    ) {
        $this->validateRequest();
    }

    /**
     * Validate the request data
     */
    private function validateRequest(): void
    {
        // Validate external_id length
        if (strlen($this->external_id) < 1 || strlen($this->external_id) > 255) {
            throw new InvalidArgumentException('external_id must be between 1 and 255 characters');
        }

        // Validate URLs
        foreach (['success_redirect_url', 'failure_redirect_url'] as $urlField) {
            $url = $this->{$urlField};
            if ($url !== null) {
                if (strlen($url) < 1 || strlen($url) > 255) {
                    throw new InvalidArgumentException("$urlField must be between 1 and 255 characters");
                }
                if (! filter_var($url, FILTER_VALIDATE_URL)) {
                    throw new InvalidArgumentException("$urlField must be a valid URL");
                }
            }
        }

        // Validate invoice duration
        if ($this->invoice_duration !== null) {
            if ($this->invoice_duration < 1 || $this->invoice_duration > 31536000) {
                throw new InvalidArgumentException('invoice_duration must be between 1 and 31536000 seconds');
            }
        }

        // Validate reminder time based on unit
        if ($this->reminder_time !== null) {
            if ($this->reminder_time_unit === null) {
                throw new InvalidArgumentException('reminder_time_unit is required when reminder_time is set');
            }
            if (! $this->reminder_time_unit->isValidValue($this->reminder_time)) {
                throw new InvalidArgumentException(
                    "Invalid reminder_time value for unit {$this->reminder_time_unit->value}"
                );
            }
        }

        // Validate metadata constraints
        if ($this->metadata !== null) {
            if (count($this->metadata) > 50) {
                throw new InvalidArgumentException('metadata cannot have more than 50 keys');
            }
            foreach ($this->metadata as $key => $value) {
                if (strlen($key) > 40) {
                    throw new InvalidArgumentException('metadata keys cannot exceed 40 characters');
                }
                if (strlen($value) > 500) {
                    throw new InvalidArgumentException('metadata values cannot exceed 500 characters');
                }
            }
        }
    }
}
