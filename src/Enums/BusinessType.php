<?php

namespace Mrfansi\LaravelXendit\Enums;

enum BusinessType: string
{
    case CORPORATION = 'CORPORATION';
    case SOLE_PROPRIETOR = 'SOLE_PROPRIETOR';
    case PARTNERSHIP = 'PARTNERSHIP';
    case COOPERATIVE = 'COOPERATIVE';
    case TRUST = 'TRUST';
    case NON_PROFIT = 'NON_PROFIT';
    case GOVERNMENT = 'GOVERNMENT';
}
