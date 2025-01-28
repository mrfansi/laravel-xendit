<?php

namespace Mrfansi\XenditSdk\Data;

use Mrfansi\XenditSdk\Enums\ClientType;
use Spatie\LaravelData\Data;

class ClientTypeData extends Data
{
    /**
     * Construct a new ClientTypeData instance.
     *
     * @param  ClientType  $type  A ClientType enum value
     */
    public function __construct(
        public ClientType $type
    ) {}

    /**
     * Construct a new ClientTypeData instance from a ClientType enum value.
     *
     * @param  ClientType  $type  A ClientType enum value
     */
    public static function fromEnum(ClientType $type): self
    {
        return new self($type);
    }

    /**
     * Return the ClientType enum value.
     */
    public function toEnum(): ClientType
    {
        return $this->type;
    }
}
