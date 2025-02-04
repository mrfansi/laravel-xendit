<?php

namespace Mrfansi\LaravelXendit\Enums;

enum InvoiceStatus: string
{
    case PENDING = 'PENDING';
    case PAID = 'PAID';
    case SETTLED = 'SETTLED';
    case EXPIRED = 'EXPIRED';
}
