<?php

namespace Mrfansi\LaravelXendit\Traits;

use Illuminate\Http\Client\ConnectionException;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

trait HasHandleException
{
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
