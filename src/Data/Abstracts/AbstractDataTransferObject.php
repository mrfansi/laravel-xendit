<?php

namespace Mrfansi\Xendit\Data\Abstracts;

use Mrfansi\Xendit\Data\Contracts\DataTransferObject;
use ReflectionClass;
use ReflectionProperty;

abstract class AbstractDataTransferObject implements DataTransferObject
{
    /**
     * Convert DTO to array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [];
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $name = $property->getName();
            $value = $this->{$name};

            if ($value instanceof DataTransferObject) {
                $data[$name] = $value->toArray();
            } elseif (is_array($value)) {
                $data[$name] = array_map(function ($item) {
                    return $item instanceof DataTransferObject ? $item->toArray() : $item;
                }, $value);
            } else {
                $data[$name] = $value;
            }
        }

        return $data;
    }

    /**
     * Create DTO from array
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static
    {
        $instance = new static;
        $reflection = new ReflectionClass($instance);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $name = $property->getName();
            if (! array_key_exists($name, $data)) {
                continue;
            }

            $value = $data[$name];
            $type = $property->getType();

            if ($type && ! $type->isBuiltin()) {
                $className = $type->getName();
                if (is_subclass_of($className, DataTransferObject::class)) {
                    $instance->{$name} = $className::fromArray($value);

                    continue;
                }
            }

            $instance->{$name} = $value;
        }

        return $instance;
    }
}
