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

use CodeMagpie\ArrayToObject\Contracts\FillInterface;
use CodeMagpie\ArrayToObject\Contracts\PropertyParserInterface;
use CodeMagpie\ArrayToObject\Utils\PropertyDocParser;
use CodeMagpie\ArrayToObject\Utils\PropertyParser;

class ArrayToObjectBuilder
{
    protected PropertyParserInterface $propertyParser;

    public static function create(?PropertyParserInterface $propertyParser = null): ArrayToObjectBuilder
    {
        if (! $propertyParser) {
            $propertyParser = new PropertyParser();
        }
        $than = new static();
        $than->propertyParser = $propertyParser;
        return $than;
    }

    public static function createFormPropertyDocParser(): ArrayToObjectBuilder
    {
        return self::create(new PropertyDocParser());
    }

    public function bind(object $object, array $data): void
    {
        if ($object instanceof FillInterface) {
            $object->fill($data);
        } else {
            $className = get_class($object);
            $propertyTypes = $this->propertyParser->parseType($className);
            foreach ($propertyTypes as $propertyName => $propertyType) {
                if (array_key_exists($propertyName, $data)) {
                    $value = $data[$propertyName];
                } elseif (array_key_exists($propertyType->getPropertyNameLine(), $data) || array_key_exists($propertyType->getPropertyNameHump(), $data)) {
                    $value = $data[$propertyType->getPropertyNameLine()] ?? $data[$propertyType->getPropertyNameHump()];
                } else {
                    continue;
                }
                $object->{$propertyName} = $this->convertValue($value, $propertyType);
            }
        }
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    protected function convertValue($value, PropertyType $propertyType)
    {
        if (! $propertyType->isMixed()) {
            if (! $value && $propertyType->isNullable()) {
                $value = null;
            } elseif ($className = $propertyType->getClassName()) {
                $object = new $className();
                $this->bind($object, $value);
                return $object;
            } elseif (is_array($value) && $propertyType->getChild()) {
                foreach ($value as $index => $datum) {
                    $tempValue = self::convertValue($datum, $propertyType->getChild());
                    if ($tempValue !== $datum) {
                        $value[$index] = $tempValue;
                    }
                }
            } else {
                settype($value, $propertyType->getType());
            }
        }
        return $value;
    }
}
