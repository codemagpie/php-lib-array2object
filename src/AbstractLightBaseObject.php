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
use CodeMagpie\ArrayToObject\Exception\ArrayToObjectException;
use CodeMagpie\ArrayToObject\Utils\DataHelper;
use CodeMagpie\ArrayToObject\Utils\PropertyBuffer;
use CodeMagpie\ArrayToObject\Utils\PropertyDocParser;
use Hyperf\Utils\Contracts\Arrayable;

abstract class AbstractLightBaseObject implements FillInterface, Arrayable, ArrayAccess
{
    public function __construct(array $data)
    {
        $data && $this->fill($data);
    }

    private array $__attributes = [];

    public function __unset($name)
    {
        unset($this->__attributes[$name]);
    }

    public function __isset($name)
    {
        return isset($this->__attributes[$name]);
    }

    public function __set($name, $value)
    {
        $propertyTypes = PropertyBuffer::getPropertyTypes(new PropertyDocParser(), static::class);
        if (array_key_exists($name, $propertyTypes)) {
            $this->__attributes[$name] = $value;
        } else {
            throw new ArrayToObjectException(sprintf('This object is not the %s attribute', $name));
        }
    }

    public function __get($name)
    {
        if (! array_key_exists($name, $this->__attributes)) {
            throw new ArrayToObjectException(sprintf('This object is not the %s attribute or the attribute has not been assigned', $name));
        }
        $value = $this->__attributes[$name];
        $propertyType = PropertyBuffer::getPropertyTypes(new PropertyDocParser(), static::class)[$name] ?? null;
        if ($propertyType) {
            $value = DataHelper::convertValue($value, $propertyType, __CLASS__);
        }
        if ($this->__attributes[$name] !== $value) {
            $this->__attributes[$name] = $value;
        }
        return $value;
    }

    public function fill(array $data): void
    {
        $propertyTypes = PropertyBuffer::getPropertyTypes(new PropertyDocParser(), static::class);
        foreach ($propertyTypes as $propertyName => $propertyType) {
            if (array_key_exists($propertyName, $data)) {
                $value = $data[$propertyName];
            } elseif (array_key_exists($propertyType->getPropertyNameLine(), $data) || array_key_exists($propertyType->getPropertyNameHump(), $data)) {
                $value = $data[$propertyType->getPropertyNameLine()] ?? $data[$propertyType->getPropertyNameHump()];
            } else {
                continue;
            }
            $this->__attributes[$propertyName] = $value;
        }
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
        }, $this->__attributes);
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
