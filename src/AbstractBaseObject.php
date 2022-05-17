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

use CodeMagpie\ArrayToObject\Constants\PropertyModel;
use CodeMagpie\ArrayToObject\Contracts\FillInterface;
use CodeMagpie\ArrayToObject\Exception\ArrayToObjectException;
use CodeMagpie\ArrayToObject\Utils\PropertyParser;

abstract class AbstractBaseObject implements FillInterface
{
    /**
     * @var array<string,PropertyType>
     */
    private static array $__propertyTypeCache = [];

    public function __construct(array $data)
    {
        if ($data) {
            // 清除缓存，防止内存泄漏
            if (count(self::$__propertyTypeCache) > 1000) {
                self::$__propertyTypeCache = [];
            }
            $this->fill($data);
        }
    }

    public function fill(array $data): void
    {
        $propertyTypes = $this->getPropertyTypes();
        foreach ($propertyTypes as $propertyName => $propertyType) {
            if (! array_key_exists($propertyName, $data)) {
                continue;
            }
            $value = $data[$propertyName];
            $this->convertValue($value, $propertyType);
            $this->{$propertyName} = $value;
        }
    }

    protected function getPropertyTypes(): array
    {
        $propertyTypes = self::$__propertyTypeCache[static::class] ?? null;
        if (isset($propertyTypes)) {
            return $propertyTypes;
        }
        $propertyTypes = (new PropertyParser(static::class))->parseType();
        self::$__propertyTypeCache[static::class] = $propertyTypes;
        return $propertyTypes;
    }

    protected function convertValue(&$value, PropertyType $propertyType): void
    {
        if (! $propertyType->isMixed) {
            if ($propertyType->nullable && ! $value) {
                $value = null;
            } elseif ($propertyType->className) {
                if (! is_subclass_of($propertyType->className, __CLASS__)) {
                    throw new ArrayToObjectException(sprintf('the class %s must be extends %s', $propertyType->className, __CLASS__));
                }
                $value = new $propertyType->className($value);
            } elseif ($propertyType->child) {
                foreach ($value as $index => $datum) {
                    $this->convertValue($datum, $propertyType->child);
                    $value[$index] = $datum;
                }
            } else {
                settype($value, $propertyType->type);
            }
        }
    }
}
