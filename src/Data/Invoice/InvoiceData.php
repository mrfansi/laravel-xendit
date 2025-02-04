<?php

namespace Mrfansi\LaravelXendit\Data\Invoice;

use Mrfansi\LaravelXendit\Enums\Currency;
use Mrfansi\LaravelXendit\Enums\Locale;
use Mrfansi\LaravelXendit\Enums\ReminderTimeUnit;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;

class InvoiceData
{
    public function __construct(
        public string $externalId,
        public int $amount,
        public ?string $description = null,
        public ?InvoiceCustomerData $customer = null,
        public ?array $customerNotificationPreference = null,
        public ?int $invoiceDuration = null,
        public ?string $successRedirectUrl = null,
        public ?string $failureRedirectUrl = null,
        public ?array $paymentMethods = null,
        public ?Currency $currency = null,
        public ?string $callbackVirtualAccountId = null,
        public ?string $midLabel = null,
        public ?ReminderTimeUnit $reminderTimeUnit = null,
        public ?int $reminderTime = null,
        public ?Locale $locale = null,
        public ?array $items = null,
        public ?array $fees = null,
        public ?bool $shouldAuthenticateCreditCard = null,
        public ?array $channelProperties = null,
        public ?array $metadata = null,
    ) {
        $this->validate();
    }

    /**
     * @throws ValidationException
     */
    private function validate(): void
    {
        // Validate externalId
        if (strlen($this->externalId) < 1 || strlen($this->externalId) > 255) {
            throw new ValidationException('External ID must be between 1 and 255 characters');
        }

        // Validate amount
        if ($this->amount <= 0) {
            throw new ValidationException('Amount must be greater than 0');
        }

        // Validate description if provided
        if ($this->description !== null && strlen($this->description) < 1) {
            throw new ValidationException('Description must be at least 1 character');
        }

        // Validate customer notification preference if provided
        if ($this->customerNotificationPreference !== null) {
            $validChannels = ['whatsapp', 'email', 'viber'];
            $validTypes = ['invoice_created', 'invoice_reminder', 'invoice_paid'];

            foreach ($this->customerNotificationPreference as $type => $channels) {
                if (! in_array($type, $validTypes)) {
                    throw new ValidationException("Invalid notification type: $type");
                }
                if (! is_array($channels)) {
                    throw new ValidationException('Channels must be an array');
                }
                foreach ($channels as $channel) {
                    if (! in_array($channel, $validChannels)) {
                        throw new ValidationException("Invalid channel: $channel");
                    }
                }
            }
        }

        // Validate invoice duration if provided
        if ($this->invoiceDuration !== null) {
            if ($this->invoiceDuration < 1 || $this->invoiceDuration > 31536000) {
                throw new ValidationException('Invoice duration must be between 1 and 31536000 seconds');
            }
        }

        // Validate URLs if provided
        if ($this->successRedirectUrl !== null) {
            if (strlen($this->successRedirectUrl) < 1 || strlen($this->successRedirectUrl) > 255) {
                throw new ValidationException('Success redirect URL must be between 1 and 255 characters');
            }
        }

        if ($this->failureRedirectUrl !== null) {
            if (strlen($this->failureRedirectUrl) < 1 || strlen($this->failureRedirectUrl) > 255) {
                throw new ValidationException('Failure redirect URL must be between 1 and 255 characters');
            }
        }

        // Validate reminder time if provided
        if ($this->reminderTime !== null) {
            if ($this->reminderTimeUnit === ReminderTimeUnit::DAYS) {
                if ($this->reminderTime < 1 || $this->reminderTime > 30) {
                    throw new ValidationException('Reminder time must be between 1 and 30 days');
                }
            } elseif ($this->reminderTimeUnit === ReminderTimeUnit::HOURS) {
                if ($this->reminderTime < 1 || $this->reminderTime > 24) {
                    throw new ValidationException('Reminder time must be between 1 and 24 hours');
                }
            }
        }

        // Validate items if provided
        if ($this->items !== null) {
            if (count($this->items) > 75) {
                throw new ValidationException('Maximum 75 items allowed');
            }
            foreach ($this->items as $index => $item) {
                if (! isset($item['name']) || strlen($item['name']) > 256) {
                    throw new ValidationException("Item name is required and must not exceed 256 characters at index $index");
                }
                if (! isset($item['quantity']) || $item['quantity'] > 510000) {
                    throw new ValidationException("Item quantity is required and must not exceed 510000 at index $index");
                }
                if (! isset($item['price'])) {
                    throw new ValidationException("Item price is required at index $index");
                }
            }
        }

        // Validate fees if provided
        if ($this->fees !== null) {
            if (count($this->fees) > 10) {
                throw new ValidationException('Maximum 10 fees allowed');
            }
            foreach ($this->fees as $index => $fee) {
                if (! isset($fee['type'])) {
                    throw new ValidationException("Fee type is required at index $index");
                }
                if (! isset($fee['value'])) {
                    throw new ValidationException("Fee value is required at index $index");
                }
            }
        }
    }

    public function toArray(): array
    {
        $data = [
            'external_id' => $this->externalId,
            'amount' => $this->amount,
        ];

        // Add optional fields only if they are not null
        if ($this->description !== null) {
            $data['description'] = $this->description;
        }
        if ($this->customer !== null) {
            $data['customer'] = $this->customer->toArray();
        }
        if ($this->customerNotificationPreference !== null) {
            $data['customer_notification_preference'] = $this->customerNotificationPreference;
        }
        if ($this->invoiceDuration !== null) {
            $data['invoice_duration'] = $this->invoiceDuration;
        }
        if ($this->successRedirectUrl !== null) {
            $data['success_redirect_url'] = $this->successRedirectUrl;
        }
        if ($this->failureRedirectUrl !== null) {
            $data['failure_redirect_url'] = $this->failureRedirectUrl;
        }
        if ($this->paymentMethods !== null) {
            $data['payment_methods'] = $this->paymentMethods;
        }
        if ($this->currency !== null) {
            $data['currency'] = $this->currency->value;
        }
        if ($this->callbackVirtualAccountId !== null) {
            $data['callback_virtual_account_id'] = $this->callbackVirtualAccountId;
        }
        if ($this->midLabel !== null) {
            $data['mid_label'] = $this->midLabel;
        }
        if ($this->reminderTimeUnit !== null) {
            $data['reminder_time_unit'] = $this->reminderTimeUnit->value;
        }
        if ($this->reminderTime !== null) {
            $data['reminder_time'] = $this->reminderTime;
        }
        if ($this->locale !== null) {
            $data['locale'] = $this->locale->value;
        }
        if ($this->items !== null) {
            $data['items'] = $this->items;
        }
        if ($this->fees !== null) {
            $data['fees'] = $this->fees;
        }
        if ($this->shouldAuthenticateCreditCard !== null) {
            $data['should_authenticate_credit_card'] = $this->shouldAuthenticateCreditCard;
        }
        if ($this->channelProperties !== null) {
            $data['channel_properties'] = $this->channelProperties;
        }
        if ($this->metadata !== null) {
            $data['metadata'] = $this->metadata;
        }

        return $data;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            externalId: $data['external_id'],
            amount: $data['amount'],
            description: $data['description'] ?? null,
            customer: isset($data['customer']) ? InvoiceCustomerData::fromArray($data['customer']) : null,
            customerNotificationPreference: $data['customer_notification_preference'] ?? null,
            invoiceDuration: $data['invoice_duration'] ?? null,
            successRedirectUrl: $data['success_redirect_url'] ?? null,
            failureRedirectUrl: $data['failure_redirect_url'] ?? null,
            paymentMethods: $data['payment_methods'] ?? null,
            currency: isset($data['currency']) ? Currency::from($data['currency']) : null,
            callbackVirtualAccountId: $data['callback_virtual_account_id'] ?? null,
            midLabel: $data['mid_label'] ?? null,
            reminderTimeUnit: isset($data['reminder_time_unit']) ? ReminderTimeUnit::from($data['reminder_time_unit']) : null,
            reminderTime: $data['reminder_time'] ?? null,
            locale: isset($data['locale']) ? Locale::from($data['locale']) : null,
            items: $data['items'] ?? null,
            fees: $data['fees'] ?? null,
            shouldAuthenticateCreditCard: $data['should_authenticate_credit_card'] ?? null,
            channelProperties: $data['channel_properties'] ?? null,
            metadata: $data['metadata'] ?? null,
        );
    }
}
