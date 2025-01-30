<?php

namespace Mrfansi\Xendit\Data;

use Mrfansi\Xendit\Enums\InvoiceStatus;
use Spatie\LaravelData\Data;

/**
 * Class InvoiceStatusData
 * 
 * Represents the status of an invoice
 */
class InvoiceStatusData extends AbstractDataTransferObject
{
    /**
     * @param string|null $status Current status of the invoice (e.g., 'PENDING', 'PAID', 'EXPIRED')
     */
    public function __construct(
        public ?string $status = null,
    ) {
    }
}
