<?php

namespace Mrfansi\Xendit\Data;

use InvalidArgumentException;
use Mrfansi\Xendit\Data\Abstracts\AbstractDataTransferObject;

/**
 * Class Fee
 *
 * Represents a fee with type and amount information
 */
class Fee extends AbstractDataTransferObject
{
    /**
     * Create a new Fee instance
     *
     * @param  string|null  $type  Fee type (e.g. ADMIN, PLATFORM, DISCOUNT)
     * @param  float|null  $value  Fee value
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        public ?string $type = null,
        public ?float $value = null,
    ) {
        if ($type === '') {
            throw new InvalidArgumentException('Fee type cannot be empty');
        }
    }

    /**
     * Convert instance to array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'value' => $this->value,
        ];
    }
}
