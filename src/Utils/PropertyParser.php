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
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;

class PropertyParser implements PropertyParserInterface
{
    /**
     * @return array<string, PropertyType>
     */
    public function parseType(string $className): array
    {
        $phpDocExtractor = new PhpDocExtractor();
        $reflectionExtractor = new ReflectionExtractor();
        $propertyInfo = new PropertyInfoExtractor(
            [
                $reflectionExtractor,
            ],
            [
                $phpDocExtractor,
                $reflectionExtractor,
            ],
        );
        $properties = $propertyInfo->getProperties($className);
        $propertyTypes = [];
        foreach ($properties as $property) {
            $types = $propertyInfo->getTypes($className, $property) ?: [];
            $propertyTypes[$property] = PropertyType::createByPropertyInfoTypes($types, $property);
        }
        return $propertyTypes;
    }
}
