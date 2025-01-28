<?php

namespace Mrfansi\XenditSdk\Data;

use Mrfansi\XenditSdk\Enums\PaymentMethod;
use Spatie\LaravelData\Data;

class PaymentMethodData extends Data
{
    public function __construct(
        public PaymentMethod $method
    ) {}

    public static function fromEnum(PaymentMethod $method): self
    {
        return new self($method);
    }

    public function toEnum(): PaymentMethod
    {
        return $this->method;
    }
}
