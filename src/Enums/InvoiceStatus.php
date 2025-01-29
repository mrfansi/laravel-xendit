<?php

namespace Mrfansi\Xendit\Enums;

enum InvoiceStatus: string
{
    case PENDING = 'PENDING';
    case PAID = 'PAID';
    case SETTLED = 'SETTLED';
    case EXPIRED = 'EXPIRED';
}
