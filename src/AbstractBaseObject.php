<?php

declare(strict_types=1);
/**
 * This file is part of the codemagpie/array2object package.
 *
 * (c) CodeMagpie Lyf <https://github.com/codemagpie>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CodeMagpie\ArrayToObject;

use ArrayAccess;
use CodeMagpie\ArrayToObject\Contracts\FillInterface;
use CodeMagpie\ArrayToObject\Utils\DataHelper;
use CodeMagpie\ArrayToObject\Utils\PropertyBuffer;
use CodeMagpie\ArrayToObject\Utils\PropertyParser;
use Hyperf\Utils\Contracts\Arrayable;

abstract class AbstractBaseObject implements FillInterface, Arrayable, ArrayAccess
{
    public function __construct(array $data)
    {
        $data && $this->fill($data);
    }

    public function toArray(): array
    {
        return array_map(function ($value) {
            if ($value instanceof Arrayable) {
                $value = $value->toArray();
            } elseif (is_array($value)) {
                foreach ($value as $index => $item) {
                    $value[$index] = $item instanceof Arrayable ? $item->toArray() : $item;
                }
            }
            return $value;
        }, get_object_vars($this));
    }

    public function fill(array $data): void
    {
        $propertyTypes = PropertyBuffer::getPropertyTypes(new PropertyParser(), static::class);
        foreach ($propertyTypes as $propertyName => $propertyType) {
            if (array_key_exists($propertyName, $data)) {
                $value = $data[$propertyName];
            } elseif (array_key_exists($propertyType->getPropertyNameLine(), $data) || array_key_exists($propertyType->getPropertyNameHump(), $data)) {
                $value = $data[$propertyType->getPropertyNameLine()] ?? $data[$propertyType->getPropertyNameHump()];
            } else {
                continue;
            }
            $this->{$propertyName} = DataHelper::convertValue($value, $propertyType, __CLASS__);
        }
    }


    /**
     * @param mixed $offset
     */
    public function offsetExists($offset): bool
    {
        return isset($this->{$offset});
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->{$offset};
    }

    /**
     * @param $offset
     * @param $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->{$offset} = $value;
    }

    /**
     * @param $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->{$offset});
    }
}
