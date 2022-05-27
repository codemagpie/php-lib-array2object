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

use CodeMagpie\Utils\Utils;
use Symfony\Component\PropertyInfo\Type;

class PropertyType
{
    protected bool $isMixed = false;

    protected string $type;

    protected ?string $className = null;

    protected bool $nullable;

    protected string $propertyNameHump;

    protected string $propertyNameLine;

    protected ?PropertyType $child = null;

    /**
     * @param Type[] $types
     */
    public static function createByPropertyInfoTypes(array $types, string $propertyName): self
    {
        $propertyType = new static();
        $propertyType->propertyNameLine = Utils::stringToLine($propertyName);
        $propertyType->propertyNameHump = Utils::stringToHump($propertyName);
        if (! $types || count($types) > 1) {
            $propertyType->isMixed = true;
        } else {
            $type = current($types);
            $propertyType->type = $type->getBuiltinType();
            $propertyType->className = $type->getClassName();
            $propertyType->nullable = $type->isNullable();
            if (($valueTypes = $type->getCollectionValueTypes())) {
                $child = new static();
                $child->type = $valueTypes[0]->getBuiltinType();
                $child->className = $valueTypes[0]->getClassName();
                $child->nullable = $valueTypes[0]->isNullable();
                $propertyType->child = $child;
            }
        }
        return $propertyType;
    }

    /**
     * @return bool
     */
    public function isMixed(): bool
    {
        return $this->isMixed;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getClassName(): ?string
    {
        return $this->className;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @return string
     */
    public function getPropertyNameHump(): string
    {
        return $this->propertyNameHump;
    }

    /**
     * @return string
     */
    public function getPropertyNameLine(): string
    {
        return $this->propertyNameLine;
    }

    /**
     * @return PropertyType|null
     */
    public function getChild(): ?PropertyType
    {
        return $this->child;
    }
}
