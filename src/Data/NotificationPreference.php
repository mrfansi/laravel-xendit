<?php

namespace Mrfansi\Xendit\Data;

use Mrfansi\Xendit\AbstractDataTransferObject;

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
}
