<?php

namespace Mrfansi\LaravelXendit\Traits;

use Mrfansi\LaravelXendit\Data\Invoice\InvoiceParams;
use RuntimeException;

use function Laravel\Prompts\search;

trait HasHelperFunctions
{
    /**
     * Get invoice ID from option or search
     *
     * @throws RuntimeException When invoice ID cannot be found
     */
    private function getInvoiceByExternalID(): string
    {
        $id = search(
            'Search invoice by External ID',
            fn (string $value) => strlen($value) > 0
                ? $this->xendit->invoice()
                    ->all(
                        new InvoiceParams(
                            externalId: $value
                        )
                    )
                    ->pluck('externalId', 'id')->toArray()
                : []
        );

        if (! $id) {
            throw new RuntimeException('Invoice not found');
        }

        return $id;
    }
}
