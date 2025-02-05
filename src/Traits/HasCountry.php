<?php

namespace Mrfansi\LaravelXendit\Traits;

trait HasCountry
{
    public function getCountryCode($name): false|int|string
    {
        $countries = config('xendit.countries');

        return array_search($name, $countries);
    }
}
