<?php

namespace Mrfansi\Xendit\Data;

use InvalidArgumentException;
use Mrfansi\Xendit\Data\Abstracts\AbstractDataTransferObject;
use Mrfansi\Xendit\Enums\ReminderTimeUnit;
use ReflectionClass;

/**
 * Class CreateInvoiceRequest
 *
 * Represents a request to create a new invoice
 */
class CreateInvoiceRequest extends AbstractDataTransferObject
{
    /**
     * @param  string|null  $external_id  External ID for the invoice
     * @param  float|null  $amount  Amount to charge
     * @param  string|null  $description  Description of the invoice
     * @param  string|null  $invoice_url  URL where the invoice can be viewed
     * @param  string|null  $success_redirect_url  URL to redirect after successful payment
     * @param  string|null  $failure_redirect_url  URL to redirect after failed payment
     * @param  string|null  $payment_methods  Payment methods to accept
     * @param  bool|null  $should_authenticate  Whether to require authentication
     * @param  string|null  $currency  Currency for the invoice
     * @param  int|null  $reminder_time  Time to send reminder
     * @param  ReminderTimeUnit|null  $reminder_time_unit  Unit for reminder time
     * @param  Customer|null  $customer  Customer details
     * @param  NotificationPreference|null  $customer_notification_preference  Customer notification preferences
     * @param  array<Item>|null  $items  Items in the invoice
     * @param  array<Fee>|null  $fees  Fees to charge
     * @param  int|null  $invoice_duration  Duration before invoice expires (in seconds)
     * @param  array<string, mixed>|null  $metadata  Additional metadata
     */
    public function __construct(
        public ?string $external_id = null,
        public ?float $amount = null,
        public ?string $description = null,
        public ?string $invoice_url = null,
        public ?string $success_redirect_url = null,
        public ?string $failure_redirect_url = null,
        public ?string $payment_methods = null,
        public ?bool $should_authenticate = null,
        public ?string $currency = null,
        public ?int $reminder_time = null,
        public ?ReminderTimeUnit $reminder_time_unit = null,
        public ?Customer $customer = null,
        public ?NotificationPreference $customer_notification_preference = null,
        public ?array $items = null,
        public ?array $fees = null,
        public ?int $invoice_duration = null,
        public ?array $metadata = null,
    ) {
        $this->validateParams();
    }

    /**
     * Validate the request parameters
     */
    private function validateParams(): void
    {
        if ($this->external_id !== null) {
            if (strlen($this->external_id) === 0) {
                throw new InvalidArgumentException('external_id must be between 1 and 255 characters');
            }

            if (strlen($this->external_id) > 255) {
                throw new InvalidArgumentException('external_id must be between 1 and 255 characters');
            }
        }

        if ($this->success_redirect_url !== null) {
            if (! filter_var($this->success_redirect_url, FILTER_VALIDATE_URL)) {
                throw new InvalidArgumentException('success_redirect_url must be a valid URL');
            }

            if (strlen($this->success_redirect_url) > 255) {
                throw new InvalidArgumentException('success_redirect_url must be between 1 and 255 characters');
            }
        }

        if ($this->failure_redirect_url !== null) {
            if (! filter_var($this->failure_redirect_url, FILTER_VALIDATE_URL)) {
                throw new InvalidArgumentException('failure_redirect_url must be a valid URL');
            }

            if (strlen($this->failure_redirect_url) > 255) {
                throw new InvalidArgumentException('failure_redirect_url must be between 1 and 255 characters');
            }
        }

        if ($this->invoice_duration !== null) {
            if ($this->invoice_duration < 1) {
                throw new InvalidArgumentException('invoice_duration must be between 1 and 31536000 seconds');
            }

            if ($this->invoice_duration > 31536000) {
                throw new InvalidArgumentException('invoice_duration must be between 1 and 31536000 seconds');
            }
        }

        if ($this->reminder_time !== null && $this->reminder_time_unit === null) {
            throw new InvalidArgumentException('reminder_time_unit is required when reminder_time is set');
        }

        if ($this->reminder_time !== null && $this->reminder_time_unit !== null) {
            $maxValue = $this->reminder_time_unit->getMaxValue();
            if ($this->reminder_time > $maxValue) {
                throw new InvalidArgumentException("reminder_time cannot exceed {$maxValue} {$this->reminder_time_unit->value}");
            }
        }

        if ($this->metadata !== null) {
            if (count($this->metadata) > 50) {
                throw new InvalidArgumentException('metadata cannot have more than 50 keys');
            }

            foreach ($this->metadata as $key => $value) {
                if (strlen($key) > 40) {
                    throw new InvalidArgumentException('metadata keys cannot exceed 40 characters');
                }

                if (is_string($value) && strlen($value) > 255) {
                    throw new InvalidArgumentException('metadata value cannot be longer than 255 characters');
                }
            }
        }
    }

    /**
     * Create an instance from array data
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        $instance = (new ReflectionClass(static::class))->newInstance();

        $instance->external_id = $data['external_id'] ?? null;
        $instance->amount = $data['amount'] ?? null;
        $instance->description = $data['description'] ?? null;
        $instance->invoice_url = $data['invoice_url'] ?? null;
        $instance->success_redirect_url = $data['success_redirect_url'] ?? null;
        $instance->failure_redirect_url = $data['failure_redirect_url'] ?? null;
        $instance->payment_methods = $data['payment_methods'] ?? null;
        $instance->should_authenticate = $data['should_authenticate'] ?? null;
        $instance->currency = $data['currency'] ?? null;
        $instance->reminder_time = $data['reminder_time'] ?? null;
        $instance->reminder_time_unit = isset($data['reminder_time_unit']) ? ReminderTimeUnit::from($data['reminder_time_unit']) : null;
        $instance->customer = isset($data['customer']) ? Customer::fromArray($data['customer']) : null;
        $instance->customer_notification_preference = isset($data['customer_notification_preference']) ? NotificationPreference::fromArray($data['customer_notification_preference']) : null;
        $instance->items = isset($data['items']) ? array_map(fn ($item) => Item::fromArray($item), $data['items']) : null;
        $instance->fees = isset($data['fees']) ? array_map(fn ($fee) => Fee::fromArray($fee), $data['fees']) : null;
        $instance->invoice_duration = $data['invoice_duration'] ?? null;
        $instance->metadata = $data['metadata'] ?? null;

        return $instance;
    }
}
