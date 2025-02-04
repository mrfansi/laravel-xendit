<?php

namespace Mrfansi\LaravelXendit;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Mrfansi\LaravelXendit\Contracts\HttpClientFactory;
use Mrfansi\LaravelXendit\Contracts\XenditFactory;
use Mrfansi\LaravelXendit\Xendit\Invoice;
use RuntimeException;

class Xendit implements XenditFactory, HttpClientFactory
{
    /**
     * HTTP client instance for making API requests
     */
    private PendingRequest $client;

    /**
     * Optional user ID for xenPlatform sub-account transactions
     */
    private ?string $forUserId = null;

    /**
     * Optional split rule ID for routing payments to multiple accounts
     */
    private ?string $withSplitRule = null;

    /**
     * A unique key to prevent processing duplicate requests.
     * Can be your reference_id or any GUID.
     * It Must be unique across development and production environments.
     */
    private ?string $idempotencyKey = null;

    /**
     * Constructor for Xendit factory
     *
     * @throws InvalidArgumentException When required configuration is missing
     */
    public function __construct()
    {
        $this->validateConfiguration();
        $this->initializeHttpClient();
    }

    /**
     * Sets the for-user-id header for xenPlatform sub-account transactions
     *
     * @param  string  $userId  The sub-account user ID
     */
    public function withUserId(string $userId): self
    {
        $this->forUserId = $userId;
        $this->initializeHttpClient(); // Reinitialize client with new header

        return $this;
    }

    /**
     * Sets the with-split-rule header for routing payments to multiple accounts
     *
     * @param  string  $splitRuleId  The split rule ID to apply
     */
    public function withSplitRule(string $splitRuleId): self
    {
        $this->withSplitRule = $splitRuleId;
        $this->initializeHttpClient(); // Reinitialize client with new header

        return $this;
    }

    /**
     * Sets the idempotency key header for preventing duplicate requests.
     *
     * @param  string  $idempotencyKey  The unique key to prevent processing duplicate requests.
     *                                  Can be your reference_id or any GUID.
     *                                  It Must be unique across development and production environments.
     */
    public function withIdempotencyKey(string $idempotencyKey): self
    {
        $this->idempotencyKey = $idempotencyKey;
        $this->initializeHttpClient(); // Reinitialize client with new header

        return $this;
    }

    /**
     * Initializes the HTTP client with base configuration
     */
    private function initializeHttpClient(): void
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        // Add optional headers if set
        if ($this->forUserId !== null) {
            $headers['for-user-id'] = $this->forUserId;
        }

        if ($this->withSplitRule !== null) {
            $headers['with-split-rule'] = $this->withSplitRule;
        }

        if ($this->idempotencyKey !== null) {
            $headers['Idempotency-key'] = $this->idempotencyKey;
        }

        $this->client = Http::baseUrl(config('xendit.endpoint'))
            ->withBasicAuth(config('xendit.secret_key'), '')
            ->withHeaders($headers)
            ->throw();
    }

    /**
     * Validates that all required configuration values are present
     *
     * @throws InvalidArgumentException When required configuration is missing
     */
    private function validateConfiguration(): void
    {
        if (empty(config('xendit.endpoint'))) {
            throw new InvalidArgumentException('Xendit API endpoint must be configured');
        }

        if (empty(config('xendit.secret_key'))) {
            throw new InvalidArgumentException('Xendit API key must be configured');
        }
    }

    /**
     * Returns the configured HTTP client
     *
     * @return PendingRequest The configured HTTP client
     */
    public function getClient(): PendingRequest
    {
        return $this->client;
    }

    /**
     * Creates and returns a new Invoice API instance
     *
     * @return Invoice A configured Invoice instance for making API requests
     *
     * @throws RuntimeException If dependencies cannot be resolved
     */
    public function invoice(): Invoice
    {
        return new Invoice($this->getClient());
    }
}
