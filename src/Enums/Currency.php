<?php

namespace Mrfansi\Xendit\Enums;

enum Currency: string
{
    case IDR = 'IDR'; // Indonesian Rupiah
    case PHP = 'PHP'; // Philippine Peso
    case THB = 'THB'; // Thai Baht
    case VND = 'VND'; // Vietnamese Dong
    case MYR = 'MYR'; // Malaysian Ringgit

    /**
     * Get currency for a specific country
     */
    public static function getCurrencyByCountry(CountryCode $countryCode): self
    {
        return match ($countryCode) {
            CountryCode::INDONESIA => self::IDR,
            CountryCode::PHILIPPINES => self::PHP,
            CountryCode::THAILAND => self::THB,
            CountryCode::VIETNAM => self::VND,
            CountryCode::MALAYSIA => self::MYR,
        };
    }
}
