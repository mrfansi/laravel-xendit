<?php

namespace Mrfansi\LaravelXendit\Data\Invoice;

use Carbon\Carbon;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;

class InvoiceParams
{
    /**
     * Constructor for invoice request parameters
     *
     * @param  string|null  $externalId  The external id used during generation of invoice. Given that external_id is non-unique,
     *                                   by including external_id, it will return all invoices with that specific external_id.
     * @param  array<string>|null  $statuses  Array of statuses to filter invoices. Valid values: PENDING, PAID, SETTLED, EXPIRED
     * @param  int|null  $limit  A limit on the number of invoice objects to be returned (1-100, default: 10)
     * @param  string|null  $createdAfter  Return invoices where the created field is greater than this value (ISO 8601)
     * @param  string|null  $createdBefore  Return invoices where the created field is less than this value (ISO 8601)
     * @param  string|null  $paidAfter  Return invoices where the paid_at field is greater than this value (ISO 8601)
     * @param  string|null  $paidBefore  Return invoices where the paid_at field is less than this value (ISO 8601)
     * @param  string|null  $expiredAfter  Return invoices where the expiry_date field is greater than this value (ISO 8601)
     * @param  string|null  $expiredBefore  Return invoices where the expiry_date field is less than this value (ISO 8601)
     * @param  string|null  $lastInvoiceId  A cursor for use in pagination. Last invoice ID that defines starting point.
     * @param  array<string>|null  $clientTypes  Array of client types. Values: API_GATEWAY, DASHBOARD, INTEGRATION, ON_DEMAND, RECURRING, MOBILE
     * @param  array<string>|null  $paymentChannels  Array of payment channels used to pay the invoice
     * @param  string|null  $onDemandLink  The link for the specific on demand payment
     * @param  string|null  $recurringPaymentId  The recurring payment id for specific recurring payment
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
    ) {
        $this->validate();
    }

    /**
     * Create an instance from an array of data
     *
     * @param  array<string, mixed>  $data  Array of invoice parameters
     */
    public static function fromArray(array $data): self
    {
        return new self(
            externalId: $data['external_id'] ?? null,
            statuses: $data['statuses'] ?? [],
            limit: $data['limit'] ?? 10,
            createdAfter: $data['created_after'] ?? null,
            createdBefore: $data['created_before'] ?? null,
            paidAfter: $data['paid_after'] ?? null,
            paidBefore: $data['paid_before'] ?? null,
            expiredAfter: $data['expired_after'] ?? null,
            expiredBefore: $data['expired_before'] ?? null,
            lastInvoiceId: $data['last_invoice_id'] ?? null,
            clientTypes: $data['client_types'] ?? [],
            paymentChannels: $data['payment_channels'] ?? [],
            onDemandLink: $data['on_demand_link'] ?? null,
            recurringPaymentId: $data['recurring_payment_id'] ?? null
        );
    }

    /**
     * Validate the invoice parameters
     *
     * @throws ValidationException
     */
    private function validate(): void
    {
        if ($this->limit !== null && ($this->limit < 1 || $this->limit > 100)) {
            throw new ValidationException('Limit must be between 1 and 100');
        }

        $validStatuses = ['PENDING', 'PAID', 'SETTLED', 'EXPIRED'];
        if ($this->statuses && ! empty($this->statuses)) {
            foreach ($this->statuses as $status) {
                if (! in_array($status, $validStatuses)) {
                    throw new ValidationException("Invalid status: $status");
                }
            }
        }

        $validClientTypes = ['API_GATEWAY', 'DASHBOARD', 'INTEGRATION', 'ON_DEMAND', 'RECURRING', 'MOBILE'];
        if ($this->clientTypes && ! empty($this->clientTypes)) {
            foreach ($this->clientTypes as $type) {
                if (! in_array($type, $validClientTypes)) {
                    throw new ValidationException("Invalid client type: $type");
                }
            }
        }

        $this->validateDatePair('created', $this->createdAfter, $this->createdBefore);
        $this->validateDatePair('paid', $this->paidAfter, $this->paidBefore);
        $this->validateDatePair('expired', $this->expiredAfter, $this->expiredBefore);
    }

    /**
     * Validate a pair of dates
     *
     * @param  string  $field  Field name for error message
     * @param  string|null  $after  After date
     * @param  string|null  $before  Before date
     *
     * @throws ValidationException
     */
    private function validateDatePair(string $field, ?string $after, ?string $before): void
    {
        if ($after xor $before) {
            throw new ValidationException("Both {$field}_after and {$field}_before must be provided together");
        }

        if ($after && $before) {
            try {
                $afterDate = Carbon::parse($after);
                $beforeDate = Carbon::parse($before);
            } catch (\Exception $e) {
                throw new ValidationException("Invalid ISO 8601 date format for {$field} range");
            }

            if ($afterDate->isAfter($beforeDate)) {
                throw new ValidationException("Invalid ISO 8601 date format for {$field} range");
            }
        }
    }

    /**
     * Convert the DTO to an array for API requests
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'external_id' => $this->externalId,
            'statuses' => ! empty($this->statuses) ? $this->statuses : null,
            'limit' => $this->limit,
            'created_after' => $this->createdAfter,
            'created_before' => $this->createdBefore,
            'paid_after' => $this->paidAfter,
            'paid_before' => $this->paidBefore,
            'expired_after' => $this->expiredAfter,
            'expired_before' => $this->expiredBefore,
            'last_invoice_id' => $this->lastInvoiceId,
            'client_types' => ! empty($this->clientTypes) ? $this->clientTypes : null,
            'payment_channels' => ! empty($this->paymentChannels) ? $this->paymentChannels : null,
            'on_demand_link' => $this->onDemandLink,
            'recurring_payment_id' => $this->recurringPaymentId,
        ], fn ($value) => $value !== null);
    }
}
