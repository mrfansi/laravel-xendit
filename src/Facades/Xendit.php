<?php

namespace Mrfansi\LaravelXendit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mrfansi\LaravelXendit\Xendit
 */
class Xendit extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mrfansi\LaravelXendit\Xendit::class;
    }
}
