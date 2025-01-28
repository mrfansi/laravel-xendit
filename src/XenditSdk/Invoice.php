<?php

namespace Mrfansi\XenditSdk\XenditSdk;

use Illuminate\Http\Client\PendingRequest;

class Invoice
{
    /**
     * HTTP client instance for making API requests
     */
    private PendingRequest $client;

    /**
     * Constructor for Invoice API client
     *
     * @param PendingRequest $client HTTP client for making API requests
     */
    public function __construct(PendingRequest $client)
    {
        $this->client = $client;
    }

}
