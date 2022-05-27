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
namespace CodeMagpie\ArrayToObject\Utils;

use CodeMagpie\ArrayToObject\Exception\ArrayToObjectException;
use CodeMagpie\ArrayToObject\PropertyType;

class DataHelper
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public static function convertValue($value, PropertyType $propertyType, string $checkClassType)
    {
        if (! is_subclass_of($value, $checkClassType) && ! $propertyType->isMixed()) {
            if (! $value && $propertyType->isNullable()) {
                $value = null;
            } elseif ($className = $propertyType->getClassName()) {
                if (! is_subclass_of($className, $checkClassType)) {
                    throw new ArrayToObjectException(sprintf('the class %s must be extends %s', $className, __CLASS__));
                }
                $value = new $className($value);
            } elseif (is_array($value) && $propertyType->getChild()) {
                foreach ($value as $index => $datum) {
                    $tempValue = self::convertValue($datum, $propertyType->getChild(), $checkClassType);
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

    public static function objectToArray(object $object): array
    {
        return array_map(function ($value) {
            if (is_object($value)) {
                $value = self::objectToArray($value);
            } elseif (is_array($value)) {
                foreach ($value as $index => $item) {
                    $value[$index] = is_object($item) ? self::objectToArray($item) : $item;
                }
            }
            return $value;
        }, get_object_vars($object));
    }
}
