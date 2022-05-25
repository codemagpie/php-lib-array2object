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

use CodeMagpie\ArrayToObject\Contracts\PropertyParserInterface;
use CodeMagpie\ArrayToObject\PropertyType;

class PropertyBuffer
{
    public static int $max = 1000;

    /**
     * @var array<string,PropertyType>
     */
    public static array $__propertyTypeCache = [];

    /**
     * @return array<string,PropertyType>
     */
    public static function getPropertyTypes(PropertyParserInterface $propertyParser, string $className): array
    {
        // released cache
        if (count(self::$__propertyTypeCache) > self::$max) {
            self::$__propertyTypeCache = [];
        }
        $propertyTypes = self::$__propertyTypeCache[$className] ?? null;
        if (isset($propertyTypes)) {
            return $propertyTypes;
        }
        $propertyTypes = $propertyParser->parseType($className);
        self::$__propertyTypeCache[$className] = $propertyTypes;
        return $propertyTypes;
    }
}
