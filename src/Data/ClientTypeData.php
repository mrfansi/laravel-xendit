<?php

namespace Mrfansi\XenditSdk\Data;

use Mrfansi\XenditSdk\Enums\ClientType;
use Spatie\LaravelData\Data;

class ClientTypeData extends Data
{
    public function __construct(
        public ClientType $type
    ) {}

    public static function fromEnum(ClientType $type): self
    {
        return new self($type);
    }

    public function toEnum(): ClientType
    {
        return $this->type;
    }
}
