<?php

namespace Mrfansi\LaravelXendit\Data;

class InvoiceParams
{
    /**
     * Constructor for invoice request
     *
     * @param  string|null  $externalId  External ID to filter invoices
     * @param  array<string>|null  $statuses  Array of statuses to filter invoices. Accepted values are
     * @param  int|null  $limit  Limit of invoices to retrieve
     * @param  string|null  $createdAfter  Date to filter invoices created after
     * @param  string|null  $createdBefore  Date to filter invoices created before
     * @param  string|null  $paidAfter  Date to filter invoices paid after
     * @param  string|null  $paidBefore  Date to filter invoices paid before
     * @param  string|null  $expiredAfter  Date to filter invoices expired after
     * @param  string|null  $expiredBefore  Date to filter invoices expired before
     * @param  string|null  $lastInvoiceId  Invoice ID to start pagination
     * @param  array<string>|null  $clientTypes  Array of client types to filter invoices. Accepted values are
     * @param  array<string>|null  $paymentChannels  Array of payment channels to filter invoices. Accepted values are
     * @param  string|null  $onDemandLink  On-demand link to filter invoices
     * @param  string|null  $recurringPaymentId  Recurring payment ID to filter invoices
     *
     * @see https://developers.xendit.co/api-reference/#list-all-invoices
     */
    public function __construct(
        public ?string $externalId = null,
        /** @var array<string> */
        public ?array $statuses = [],
        public ?int $limit = 10,
        public ?string $createdAfter = null,
        public ?string $createdBefore = null,
        public ?string $paidAfter = null,
        public ?string $paidBefore = null,
        public ?string $expiredAfter = null,
        public ?string $expiredBefore = null,
        public ?string $lastInvoiceId = null,
        /** @var array<string> */
        public ?array $clientTypes = [],
        /** @var array<string> */
        public ?array $paymentChannels = [],
        public ?string $onDemandLink = null,
        public ?string $recurringPaymentId = null
    ) {}

    /**
     * Convert the DTO to an array for API requests
     *
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return array_filter([
            'external_id' => $this->externalId,
            'statuses' => $this->statuses,
            'limit' => $this->limit,
            'created_after' => $this->createdAfter,
            'created_before' => $this->createdBefore,
            'paid_after' => $this->paidAfter,
            'paid_before' => $this->paidBefore,
            'expired_after' => $this->expiredAfter,
            'expired_before' => $this->expiredBefore,
            'last_invoice_id' => $this->lastInvoiceId,
            'client_types' => $this->clientTypes,
            'payment_channels' => $this->paymentChannels,
            'on_demand_link' => $this->onDemandLink,
            'recurring_payment_id' => $this->recurringPaymentId,
        ], fn ($value) => $value !== null);
    }
}
