<?php

namespace Mrfansi\XenditSdk\Data;

use DateTimeInterface;
use InvalidArgumentException;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class InvoiceParams extends Data
{
    public function __construct(
        /**
         * The external ID used during generation of invoice
         */
        public ?string $external_id = null,

        /**
         * Status of the invoices to filter
         *
         * @var InvoiceStatusData[]|null
         */
        #[DataCollectionOf(InvoiceStatusData::class)]
        public ?DataCollection $statuses = null,

        /**
         * Number of invoices to return (1-100)
         */
        public ?int $limit = 10,

        /**
         * Filter invoices created after this datetime
         */
        public ?DateTimeInterface $created_after = null,

        /**
         * Filter invoices created before this datetime
         */
        public ?DateTimeInterface $created_before = null,

        /**
         * Filter invoices paid after this datetime
         */
        public ?DateTimeInterface $paid_after = null,

        /**
         * Filter invoices paid before this datetime
         */
        public ?DateTimeInterface $paid_before = null,

        /**
         * Filter invoices expiring after this datetime
         */
        public ?DateTimeInterface $expired_after = null,

        /**
         * Filter invoices expiring before this datetime
         */
        public ?DateTimeInterface $expired_before = null,

        /**
         * Cursor for pagination (invoice ID)
         */
        public ?string $last_invoice_id = null,

        /**
         * Methods used to create the invoices
         *
         * @var ClientTypeData[]|null
         */
        #[DataCollectionOf(ClientTypeData::class)]
        public ?DataCollection $client_types = null,

        /**
         * Payment channels used
         *
         * @var PaymentMethodData[]|null
         */
        #[DataCollectionOf(PaymentMethodData::class)]
        public ?DataCollection $payment_channels = null,

        /**
         * Filter by specific on-demand link
         */
        public ?string $on_demand_link = null,

        /**
         * Filter by specific recurring payment ID
         */
        public ?string $recurring_payment_id = null,
    ) {
        $this->validateParams();
    }

    public function all(): array
    {
        $data = parent::all();

        // Convert DataCollections to arrays of enum values
        if ($this->statuses) {
            $data['statuses'] = array_map(
                fn (InvoiceStatusData $status) => $status->status->value,
                $this->statuses->items()
            );
        }

        if ($this->client_types) {
            $data['client_types'] = array_map(
                fn (ClientTypeData $type) => $type->type->value,
                $this->client_types->items()
            );
        }

        if ($this->payment_channels) {
            $data['payment_channels'] = array_map(
                fn (PaymentMethodData $method) => $method->method->value,
                $this->payment_channels->items()
            );
        }

        return $data;
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
}
