<?php

namespace Mrfansi\LaravelXendit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Mrfansi\LaravelXendit\Data\Invoice\InvoiceParams;
use Mrfansi\LaravelXendit\Data\Invoice\InvoiceResponse;
use Mrfansi\LaravelXendit\Helpers\Generator;
use Mrfansi\LaravelXendit\Traits\HasDispatchActions;
use Mrfansi\LaravelXendit\Traits\HasHelperFunctions;
use Mrfansi\LaravelXendit\Xendit;
use RuntimeException;
use Throwable;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\form;
use function Laravel\Prompts\spin;

class InvoiceCommand extends Command
{
    use HasDispatchActions;
    use HasHelperFunctions;

    public array $actions = ['all', 'find', 'new', 'expire'];

    public $signature = 'xendit:invoice
                         {action=all : Action to perform (all/find/new/expire)}
                         {--advanced : Advanced search}
                         {--id= : Invoice ID for find/expire actions}';

    public $description = 'Manage Xendit invoices for your account';

    /**
     * Xendit factory instance
     */
    private Xendit $xendit;

    public function __construct(Xendit $xendit)
    {
        parent::__construct();
        $this->xendit = $xendit;
    }

    public function handle(): void
    {
        try {
            $action = $this->argument('action');
            $this->dispatchAction($action);
        } catch (InvalidArgumentException $e) {
            $this->error('[INVALID_INPUT] '.$e->getMessage());
        } catch (RuntimeException $e) {
            $this->error('[API_ERROR] '.$e->getMessage());
        } catch (Throwable $e) {
            $this->error('[UNEXPECTED_ERROR] '.$e->getMessage());
            if (app()->environment('local')) {
                $this->error($e->getTraceAsString());
            }
        }
    }

    public function all(): void
    {
        $params = [];

        $advanced = $this->option('advanced') ?: confirm(label: 'Do you really want to advanced search?', default: false);

        if ($advanced) {
            $params = array_filter(form()
                ->multiselect(
                    label: 'Status',
                    options: [
                        'PENDING' => 'Pending',
                        'SETTLED' => 'Settled',
                        'EXPIRED' => 'Expired',
                        'PAID' => 'Paid',
                    ],
                    default: [
                        'PENDING',
                        'SETTLED',
                        'EXPIRED',
                        'PAID',
                    ],
                    hint: 'Available status: PENDING, PAID, SETTLED, EXPIRED',
                    name: 'statuses',
                )
                ->submit());
        }

        $invoiceParams = InvoiceParams::fromArray($params);

        /** @var Collection<InvoiceResponse> $invoices */
        $invoices = spin(
            fn () => $this->xendit->invoice()
                ->all($invoiceParams),
            'Fetching invoices...'
        );

        $invoices = collect($invoices)->map(function (InvoiceResponse $invoice) {
            return [
                'ID' => $invoice->id,
                'Customer Name' => $invoice->customer->givenNames,
                'Customer Email' => $invoice->customer->email,
                'Customer Phone' => $invoice->customer->mobileNumber,
                'Amount' => $invoice->amount,
                'Currency' => $invoice->currency->value,
                'Status' => $invoice->status,
            ];
        });

        $rows = Generator::getTable($invoices);
        $this->table(...$rows);
    }

    public function find(): void
    {
        $id = $this->option('id') ?? $this->getInvoiceByExternalID();

        $invoice = spin(
            fn () => $this->xendit->invoice()
                ->retrieve($id),
            'Fetching invoice...'
        );

        dd($invoice);
    }
}