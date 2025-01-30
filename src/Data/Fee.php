<?php

namespace Mrfansi\Xendit\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Missing;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Payload;
use Spatie\LaravelData\Properties\ExistsProperty;
use Spatie\LaravelData\Properties\Property;
use Spatie\LaravelData\Rules\Rule;
use Spatie\LaravelData\ValidatingData;
use Spatie\LaravelData\WithCast;
use Spatie\LaravelData\WithMapInputNames;
use Spatie\LaravelData\WithMapOutputNames;
use Spatie\LaravelData\WithTransformer;
use Spatie\LaravelData\WithValidation;

/**
 * Class Fee
 * 
 * Represents a fee with type and amount information
 */
class Fee extends AbstractDataTransferObject
{
    /**
     * @param string|null $type Type of the fee (e.g., 'ADMIN', 'SERVICE', etc.)
     * @param float|null $value Amount of the fee
     */
    public function __construct(
        public ?string $type = null,
        public ?float $value = null,
    ) {
    }
}
