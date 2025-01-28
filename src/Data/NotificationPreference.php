<?php

namespace Mrfansi\XenditSdk\Data;

use InvalidArgumentException;
use Mrfansi\XenditSdk\Enums\NotificationChannel;
use Spatie\LaravelData\Data;

class NotificationPreference extends Data
{
    /**
     * Notification preferences for different invoice events.
     *
     * @param  NotificationChannel[]|null  $invoice_created  Channels to notify when invoice is created
     * @param  NotificationChannel[]|null  $invoice_reminder  Channels to notify for payment reminders
     * @param  NotificationChannel[]|null  $invoice_paid  Channels to notify when invoice is paid
     */
    public function __construct(
        /** @var NotificationChannel[]|null */
        public readonly ?array $invoice_created = null,
        /** @var NotificationChannel[]|null */
        public readonly ?array $invoice_reminder = null,
        /** @var NotificationChannel[]|null */
        public readonly ?array $invoice_paid = null,
    ) {
        $this->validateChannels($invoice_created, 'invoice_created');
        $this->validateChannels($invoice_reminder, 'invoice_reminder');
        $this->validateChannels($invoice_paid, 'invoice_paid');
    }

    /**
     * Creates a NotificationPreference instance from an array.
     *
     * @param  array<string, mixed>  $data  The array containing notification preferences
     */
    public static function fromArray(array $data): static
    {
        $channels = ['invoice_created', 'invoice_reminder', 'invoice_paid'];
        $preferences = [];

        foreach ($channels as $channel) {
            if (isset($data[$channel]) && is_array($data[$channel])) {
                $preferences[$channel] = array_map(
                    fn (string $value) => NotificationChannel::from($value),
                    $data[$channel]
                );
            } else {
                $preferences[$channel] = null;
            }
        }

        return new static(
            invoice_created: $preferences['invoice_created'],
            invoice_reminder: $preferences['invoice_reminder'],
            invoice_paid: $preferences['invoice_paid']
        );
    }

    /**
     * Converts the NotificationPreference instance to an array.
     *
     * @return array<string, array<string>|null>
     */
    public function toArray(): array
    {
        return array_filter([
            'invoice_created' => $this->invoice_created ? array_map(
                fn (NotificationChannel $channel) => $channel->value,
                $this->invoice_created
            ) : null,
            'invoice_reminder' => $this->invoice_reminder ? array_map(
                fn (NotificationChannel $channel) => $channel->value,
                $this->invoice_reminder
            ) : null,
            'invoice_paid' => $this->invoice_paid ? array_map(
                fn (NotificationChannel $channel) => $channel->value,
                $this->invoice_paid
            ) : null,
        ], fn ($value) => $value !== null);
    }

    /**
     * Validates that all channels in the array are NotificationChannel instances.
     *
     * @param  NotificationChannel[]|null  $channels  Array of notification channels
     * @param  string  $field  Name of the field being validated
     *
     * @throws InvalidArgumentException When invalid channel is provided
     */
    private function validateChannels(?array $channels, string $field): void
    {
        if ($channels === null) {
            return;
        }

        foreach ($channels as $channel) {
            if (! $channel instanceof NotificationChannel) {
                throw new InvalidArgumentException(
                    "Invalid channel in {$field}. Must be instance of NotificationChannel enum."
                );
            }
        }
    }
}
