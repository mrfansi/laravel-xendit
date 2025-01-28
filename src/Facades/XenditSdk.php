<?php

namespace Mrfansi\XenditSdk\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mrfansi\XenditSdk\XenditSdk
 */
class XenditSdk extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mrfansi\XenditSdk\XenditSdk::class;
    }
}
