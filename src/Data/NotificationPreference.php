<?php

namespace Mrfansi\Xendit\Data;

use Mrfansi\Xendit\Data\Abstracts\AbstractDataTransferObject;
use ReflectionClass;

/**
 * Class NotificationPreference
 *
 * Represents notification preferences for various events
 */
class NotificationPreference extends AbstractDataTransferObject
{
    /**
     * @param  string|null  $invoicePaid  Notification URL for invoice paid events
     * @param  string|null  $invoiceExpired  Notification URL for invoice expired events
     * @param  array<string>|null  $whitelistedFields  List of fields to be included in the notification
     */
    public function __construct(
        public ?string $invoicePaid = null,
        public ?string $invoiceExpired = null,
        public ?array $whitelistedFields = null,
    ) {}

    /**
     * Validate notification URLs
     *
     * @param  string|null  $url  URL to validate
     */
    protected function isValidUrl(?string $url): bool
    {
        if ($url === null) {
            return true;
        }

        return filter_var($url, FILTER_VALIDATE_URL) !== false;
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
        $instance->invoicePaid = $data['invoice_paid'] ?? null;
        $instance->invoiceExpired = $data['invoice_expired'] ?? null;
        $instance->whitelistedFields = $data['whitelisted_fields'] ?? null;
        return $instance;
    }

    /**
     * Convert instance to array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'invoice_paid' => $this->invoicePaid,
            'invoice_expired' => $this->invoiceExpired,
            'whitelisted_fields' => $this->whitelistedFields,
        ];
    }
}
