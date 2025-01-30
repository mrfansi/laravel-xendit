<?php

namespace Mrfansi\Xendit\Data;

use DateTimeInterface;
use InvalidArgumentException;
use Mrfansi\Xendit\Data\Abstracts\AbstractDataTransferObject;

/**
 * Class InvoiceParams
 *
 * Represents parameters for creating or updating an invoice
 */
class InvoiceParams extends AbstractDataTransferObject
{
    /**
     * Initialize a new instance of InvoiceParams
     *
     * @param  string|null  $external_id  The external ID used during generation of invoice
     * @param  array|null  $statuses  Status of the invoices to filter
     * @param  int|null  $limit  Number of invoices to return (1-100)
     * @param  DateTimeInterface|null  $created_after  Filter invoices created after this datetime
     * @param  DateTimeInterface|null  $created_before  Filter invoices created before this datetime
     * @param  DateTimeInterface|null  $paid_after  Filter invoices paid after this datetime
     * @param  DateTimeInterface|null  $paid_before  Filter invoices paid before this datetime
     * @param  DateTimeInterface|null  $expired_after  Filter invoices expiring after this datetime
     * @param  DateTimeInterface|null  $expired_before  Filter invoices expiring before this datetime
     * @param  string|null  $last_invoice_id  Cursor for pagination (invoice ID)
     * @param  array|null  $client_types  Methods used to create the invoices
     * @param  array|null  $payment_channels  Payment channels used
     * @param  string|null  $on_demand_link  Filter by specific on-demand link
     * @param  string|null  $recurring_payment_id  Filter by specific recurring payment ID
     */
    public function __construct(
        public ?string $external_id = null,
        public ?array $statuses = null,
        public ?int $limit = 10,
        public ?DateTimeInterface $created_after = null,
        public ?DateTimeInterface $created_before = null,
        public ?DateTimeInterface $paid_after = null,
        public ?DateTimeInterface $paid_before = null,
        public ?DateTimeInterface $expired_after = null,
        public ?DateTimeInterface $expired_before = null,
        public ?string $last_invoice_id = null,
        public ?array $client_types = null,
        public ?array $payment_channels = null,
        public ?string $on_demand_link = null,
        public ?string $recurring_payment_id = null,
    ) {
        $this->validateParams();
    }

    /**
     * Validate the parameters
     */
    private function validateParams(): void
    {
        // Validate limit range
        if ($this->limit !== null) {
            if ($this->limit < 1 || $this->limit > 100) {
                throw new InvalidArgumentException('Limit must be between 1 and 100');
            }
        }

        // Validate created date range
        if (($this->created_after === null) !== ($this->created_before === null)) {
            throw new InvalidArgumentException(
                'Both created_after and created_before must be provided together'
            );
        }

        // Validate paid date range
        if (($this->paid_after === null) !== ($this->paid_before === null)) {
            throw new InvalidArgumentException(
                'Both paid_after and paid_before must be provided together'
            );
        }

        // Validate expired date range
        if (($this->expired_after === null) !== ($this->expired_before === null)) {
            throw new InvalidArgumentException(
                'Both expired_after and expired_before must be provided together'
            );
        }

        // Validate date ranges are in correct order
        if ($this->created_after && $this->created_before) {
            if ($this->created_after > $this->created_before) {
                throw new InvalidArgumentException('created_after must be before created_before');
            }
        }

        if ($this->paid_after && $this->paid_before) {
            if ($this->paid_after > $this->paid_before) {
                throw new InvalidArgumentException('paid_after must be before paid_before');
            }
        }

        if ($this->expired_after && $this->expired_before) {
            if ($this->expired_after > $this->expired_before) {
                throw new InvalidArgumentException('expired_after must be before expired_before');
            }
        }
    }

    /**
     * Get all parameters as an array for query string
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        $params = [];

        if ($this->external_id !== null) {
            $params['external_id'] = $this->external_id;
        }

        if ($this->statuses !== null) {
            $params['statuses'] = array_map(fn ($status) => $status->value, $this->statuses);
        }

        if ($this->limit !== null) {
            $params['limit'] = $this->limit;
        }

        if ($this->created_after !== null) {
            $params['created_after'] = $this->created_after->format('c');
        }

        if ($this->created_before !== null) {
            $params['created_before'] = $this->created_before->format('c');
        }

        if ($this->paid_after !== null) {
            $params['paid_after'] = $this->paid_after->format('c');
        }

        if ($this->paid_before !== null) {
            $params['paid_before'] = $this->paid_before->format('c');
        }

        if ($this->expired_after !== null) {
            $params['expired_after'] = $this->expired_after->format('c');
        }

        if ($this->expired_before !== null) {
            $params['expired_before'] = $this->expired_before->format('c');
        }

        if ($this->last_invoice_id !== null) {
            $params['last_invoice_id'] = $this->last_invoice_id;
        }

        if ($this->client_types !== null) {
            $params['client_types'] = array_map(fn ($type) => $type->value, $this->client_types);
        }

        if ($this->payment_channels !== null) {
            $params['payment_channels'] = array_map(fn ($channel) => $channel->value, $this->payment_channels);
        }

        if ($this->on_demand_link !== null) {
            $params['on_demand_link'] = $this->on_demand_link;
        }

        if ($this->recurring_payment_id !== null) {
            $params['recurring_payment_id'] = $this->recurring_payment_id;
        }

        return $params;
    }
}
