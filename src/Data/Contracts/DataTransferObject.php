<?php

namespace Mrfansi\Xendit\Data\Contracts;

interface DataTransferObject
{
    /**
     * Convert DTO to array
     * 
     * @return array<string, mixed>
     */
    public function toArray(): array;

    /**
     * Create DTO from array
     * 
     * @param array<string, mixed> $data
     * @return static
     */
    public static function fromArray(array $data): static;
}
