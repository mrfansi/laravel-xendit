<?php

namespace Mrfansi\LaravelXendit\Actions;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Mrfansi\LaravelXendit\Data\Invoice\InvoiceData;
use Mrfansi\LaravelXendit\Data\Invoice\InvoiceParams;
use Mrfansi\LaravelXendit\Data\Invoice\InvoiceResponse;
use RuntimeException;
use Throwable;

class Invoice
{
    /**
     * HTTP client instance for making API requests
     */
    private PendingRequest $client;

    /**
     * Constructor for Invoice API client
     *
     * @param  PendingRequest  $client  HTTP client for making API requests
     */
    public function __construct(PendingRequest $client)
    {
        $this->client = $client;
    }

    /**
     * Retrieve a list of invoices from Xendit API
     *
     * @param  InvoiceParams|null  $params  Filtering and pagination parameters
     * @return Collection<InvoiceResponse> Collection of invoice objects
     *
     * @throws Throwable When API response is not successful
     *
     * @see https://developers.xendit.co/api-reference/#list-all-invoices
     */
    public function all(?InvoiceParams $params = null): Collection
    {
        try {
            $response = $this->client->get('/v2/invoices', $params?->toArray());

            return collect($response->json())
                ->map(function (array $invoice) {
                    return InvoiceResponse::fromArray($invoice);
                });
        } catch (Throwable $e) {
            Log::error('Failed to retrieve invoices', [
                'error' => $e->getMessage(),
            ]);
            throw $this->handleException($e);
        }
    }

    /**
     * Create new invoice
     *
     * @param  InvoiceData  $data  Invoice data
     * @return InvoiceResponse Created invoice details
     *
     * @throws ConnectionException When API connection fails
     * @throws RuntimeException When API response is not successful
     * @throws Throwable
     *
     * @see https://developers.xendit.co/api-reference/#create-invoice
     */
    public function create(InvoiceData $data): InvoiceResponse
    {
        try {
            $response = $this->client->post('/v2/invoices', $data->toArray())->json();

            if (! $response->successful()) {
                throw new RuntimeException(
                    sprintf('Failed to create invoice: %s', $response->body())
                );
            }

            return InvoiceResponse::fromArray($response);
        } catch (Throwable $e) {
            Log::error('Failed to create invoice', [
                'error' => $e->getMessage(),
            ]);
            throw $this->handleException($e);
        }
    }

    /**
     * Retrieve invoice details by ID
     *
     * @param  string  $id  Invoice ID to retrieve
     * @return InvoiceResponse Invoice details
     *
     * @throws InvalidArgumentException When invoice ID is invalid
     * @throws ConnectionException When API connection fails
     * @throws RuntimeException When API response is not successful
     * @throws Throwable
     *
     * @see https://developers.xendit.co/api-reference/#get-invoice
     */
    public function retrieve(string $id): InvoiceResponse
    {
        try {
            $response = $this->client->get("/v2/invoices/$id")->json();

            if (! $response->successful()) {
                throw new RuntimeException(
                    sprintf('Failed to retrieve invoice: %s', $response->body())
                );
            }

            return InvoiceResponse::fromArray($response);
        } catch (Throwable $e) {
            Log::error('Failed to retrieve invoice', [
                'error' => $e->getMessage(),
            ]);
            throw $this->handleException($e);
        }
    }

    /**
     * Expire invoice by ID
     *
     * @param  string  $id  Invoice ID to expire
     * @return InvoiceResponse Invoice details
     *
     * @throws InvalidArgumentException When invoice ID is invalid
     * @throws ConnectionException When API connection fails
     * @throws RuntimeException When API response is not successful
     * @throws Throwable
     *
     * @see https://developers.xendit.co/api-reference/#expire-invoice
     */
    public function expire(string $id): InvoiceResponse
    {
        try {
            $response = $this->client->post("/v2/invoices/$id/expire")->json();

            if (! $response->successful()) {
                throw new RuntimeException(
                    sprintf('Failed to expire invoice: %s', $response->body())
                );
            }

            return InvoiceResponse::fromArray($response);
        } catch (Throwable $e) {
            Log::error('Failed to expire invoice', [
                'error' => $e->getMessage(),
            ]);
            throw $this->handleException($e);
        }
    }

    /**
     * Handle exception and convert to the appropriate type
     *
     * @param  Throwable  $e  Exception to handle
     * @return Throwable Converted exception
     */
    private function handleException(Throwable $e): Throwable
    {
        if ($e instanceof ConnectionException) {
            return $e;
        }

        if ($e instanceof InvalidArgumentException) {
            return $e;
        }

        return new RuntimeException($e->getMessage(), 0, $e);
    }
}
