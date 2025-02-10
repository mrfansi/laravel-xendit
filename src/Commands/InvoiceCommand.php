<?php

namespace Mrfansi\LaravelXendit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Mrfansi\LaravelXendit\Data\Invoice\InvoiceCustomerData;
use Mrfansi\LaravelXendit\Data\Invoice\InvoiceData;
use Mrfansi\LaravelXendit\Data\Invoice\InvoiceParams;
use Mrfansi\LaravelXendit\Data\Invoice\InvoiceResponse;
use Mrfansi\LaravelXendit\Enums\Currency;
use Mrfansi\LaravelXendit\Helpers\Generator;
use Mrfansi\LaravelXendit\Traits\HasDispatchActions;
use Mrfansi\LaravelXendit\Traits\HasHelperFunctions;
use Mrfansi\LaravelXendit\Xendit;
use RuntimeException;
use Throwable;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\form;
use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\text;

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
            $params = $this->getInvoiceParams();
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

        $data = collect([
            [
                'ID' => $invoice->id,
                'Customer Name' => $invoice->customer->givenNames,
                'Customer Email' => $invoice->customer->email,
                'Customer Phone' => $invoice->customer->mobileNumber,
                'Amount' => $invoice->amount,
                'Currency' => $invoice->currency->value,
                'Status' => $invoice->status,
                'Created At' => $invoice->created,
                'Updated At' => $invoice->updated,
            ],
        ]);

        $rows = Generator::getTable($data);
        $this->table(...$rows);
    }

    public function new(): void
    {
        $amount = text(
            label: 'Amount',
            required: true,
            validate: fn ($value) => is_numeric($value) ? null : 'Amount must be numeric'
        );

        $currency = select(
            label: 'Currency',
            options: [
                Currency::IDR->value => 'Indonesian Rupiah (IDR)',
                Currency::PHP->value => 'Philippine Peso (PHP)',
                Currency::THB->value => 'Thai Baht (THB)',
                Currency::VND->value => 'Vietnamese Dong (VND)',
                Currency::MYR->value => 'Malaysian Ringgit (MYR)',
            ],
            default: Currency::IDR->value
        );

        $customerEmail = text(
            label: 'Customer Email',
            required: true,
            validate: fn ($value) => filter_var($value, FILTER_VALIDATE_EMAIL) ? null : 'Invalid email format'
        );

        $customerName = text(
            label: 'Customer Name',
            required: true
        );

        $description = text(
            label: 'Description',
            required: true
        );

        // Generate a random external ID using Str::uuid()
        $externalId = (string) Str::uuid();

        $customerData = new InvoiceCustomerData(
            email: $customerEmail,
            givenNames: $customerName
        );

        $invoiceData = new InvoiceData(
            externalId: $externalId,
            amount: (float) $amount,
            currency: Currency::from($currency),
            customer: $customerData,
            description: $description
        );

        $invoice = spin(
            fn () => $this->xendit->invoice()->create($invoiceData),
            'Creating invoice...'
        );

        $this->info('Invoice created successfully!');
        $this->newLine();

        $data = collect([[
            'ID' => $invoice->id,
            'Amount' => $invoice->amount,
            'Currency' => $invoice->currency->value,
            'Status' => $invoice->status,
            'Invoice URL' => $invoice->invoiceUrl,
        ]]);

        $rows = Generator::getTable($data);
        $this->table(...$rows);
    }

    public function expire(): void
    {
        $id = $this->option('id') ?? $this->getInvoiceByExternalID();

        if (! confirm(label: "Are you sure you want to expire invoice {$id}?", default: false)) {
            $this->info('Operation cancelled.');

            return;
        }

        $invoice = spin(
            fn () => $this->xendit->invoice()->expire($id),
            'Expiring invoice...'
        );

        $this->info('Invoice expired successfully!');
        $this->newLine();

        $data = collect([[
            'ID' => $invoice->id,
            'Status' => $invoice->status,
            'Expired At' => $invoice->updated,
        ]]);

        $rows = Generator::getTable($data);
        $this->table(...$rows);
    }

    private function getInvoiceByExternalID(): string
    {
        return text(
            label: 'Invoice ID',
            required: true,
            validate: fn ($value) => ! empty($value) ? null : 'Invoice ID is required'
        );
    }

    private function getInvoiceParams(): array
    {
        return array_filter(
            form()
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
                    ],
                    hint: 'Available status: PENDING, PAID, SETTLED, EXPIRED',
                    name: 'statuses'
                )
                ->addIf(fn ($response) => in_array('PAID', $response['statuses']), function () {
                    return now()->addDays(-1);
                }, 'paid_after')
                ->addIf(fn ($response) => in_array('PAID', $response['statuses']), function () {
                    return now();
                }, 'paid_before')
                ->submit()
        );
    }
}
