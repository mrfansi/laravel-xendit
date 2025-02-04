<?php

namespace Mrfansi\LaravelXendit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Mrfansi\LaravelXendit\Data\Invoice\InvoiceResponse;
use Mrfansi\LaravelXendit\Traits\HasDispatchActions;
use Mrfansi\LaravelXendit\Xendit;
use RuntimeException;
use Throwable;
use function Laravel\Prompts\spin;


class InvoiceCommand extends Command
{
    use HasDispatchActions;

    public array $actions = ["all", "find", "new", "expire"];

    public $signature = 'xendit:invoice
                         {action=all : Action to perform (all/find/new/expire)}
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
            $this->error('[INVALID_INPUT] ' . $e->getMessage());
        } catch (RuntimeException $e) {
            $this->error('[API_ERROR] ' . $e->getMessage());
        } catch (Throwable $e) {
            $this->error('[UNEXPECTED_ERROR] ' . $e->getMessage());
            if (app()->environment('local')) {
                $this->error($e->getTraceAsString());
            }
        }
    }

    public function all()
    {
        /** @var Collection<InvoiceResponse> $invoices */
        $invoices = spin(
            fn() => $this->xendit->invoice()
                ->all(),
            'Fetching invoices...'
        );
    }
}
