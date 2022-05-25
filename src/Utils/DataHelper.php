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
    public static function stringToLine(string $string): string
    {
        $replaceString = preg_replace_callback('/([A-Z])/', function ($matches) {
            return '_' . strtolower($matches[0]);
        }, $string);

        return trim(preg_replace('/_{2,}/', '_', $replaceString), '_');
    }

    public static function stringToHump(string $string): string
    {
        return lcfirst(implode('', array_map('ucfirst', explode('_', $string))));
    }

    public static function arrayKeyToLine(array $array): array
    {
        $convert = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $convert[is_string($key) ? self::stringToLine($key) : $key] = self::arrayKeyToLine($value);
            } else {
                $convert[is_string($key) ? self::stringToLine($key) : $key] = $value;
            }
        }
        return $convert;
    }

    public static function arrayKeyToHump(array $array): array
    {
        $convert = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $convert[is_string($key) ? self::stringToHump($key) : $key] = self::arrayKeyToHump($value);
            } else {
                $convert[is_string($key) ? self::stringToHump($key) : $key] = $value;
            }
        }
        return $convert;
    }

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

    public static function objectToArray(object $object)
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
