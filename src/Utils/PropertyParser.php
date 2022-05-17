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
    protected string $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    /**
     * @return array<string, null|PropertyType>
     */
    public function parseType(): array
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
        $properties = $propertyInfo->getProperties($this->className);
        $propertyTypes = [];
        foreach ($properties as $property) {
            $types = $propertyInfo->getTypes($this->className, $property) ?: [];
            $propertyType = new PropertyType();
            if (! $types || count($types) > 1) {
                $propertyType->isMixed = true;
            } else {
                $type = current($types);
                $propertyType->type = $type->getBuiltinType();
                $propertyType->className = $type->getClassName();
                $propertyType->nullable = $type->isNullable();
                if (($valueTypes = $type->getCollectionValueTypes())) {
                    $child = new PropertyType();
                    $child->type = $valueTypes[0]->getBuiltinType();
                    $child->className = $valueTypes[0]->getClassName();
                    $child->nullable = $valueTypes[0]->isNullable();
                    $propertyType->child = $child;
                }
            }
            $propertyTypes[$property] = $propertyType;
        }
        return $propertyTypes;
    }
}
