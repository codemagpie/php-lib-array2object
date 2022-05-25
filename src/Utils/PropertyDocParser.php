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
use phpDocumentor\Reflection\DocBlock\Tags\InvalidTag;
use phpDocumentor\Reflection\DocBlock\Tags\Property;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Types\ContextFactory;
use ReflectionClass;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Component\PropertyInfo\Util\PhpDocTypeHelper;

class PropertyDocParser implements PropertyParserInterface
{
    protected string $className;

    /**
     * @return array<string,PropertyType>
     */
    public function parseType(string $className): array
    {
        $ref = new ReflectionClass($className);
        $this->className = $className;
        $propertyTypes = $this->getTypes($ref);
        $traitPropertyTypes = [];
        foreach ($ref->getTraits() as $trait) {
            if ($traitPropertyType = $this->getTypes($trait)) {
                $traitPropertyTypes[] = $traitPropertyType;
            }
        }
        return array_merge($propertyTypes, array_merge(...$traitPropertyTypes));
    }

    protected function getTypes(ReflectionClass $ref)
    {
        $factory = DocBlockFactory::createInstance();
        $phpDocTypeHelper = new PhpDocTypeHelper();
        $docBlock = $factory->create($ref, (new ContextFactory())->createFromReflector($ref));
        $parentClass = null;
        $tag = 'property';
        $propertyTypes = [];
        foreach ($docBlock->getTagsByName($tag) as $tag) {
            /** @var Property $tag */
            if (! $propertyName = $tag->getVariableName()) {
                continue;
            }
            $types = [];
            if (! $tag instanceof InvalidTag && $tag->getType() !== null) {
                foreach ($phpDocTypeHelper->getTypes($tag->getType()) as $type) {
                    switch ($type->getClassName()) {
                        case 'self':
                        case 'static':
                            $resolvedClass = $this->className;
                            break;
                        case 'parent':
                            if (false !== $resolvedClass = $parentClass ?? $parentClass = get_parent_class($this->className)) {
                                break;
                            }
                        // no break

                        default:
                            $types[] = $type;
                            continue 2;
                    }
                    $types[] = new Type(Type::BUILTIN_TYPE_OBJECT, $type->isNullable(), $resolvedClass, $type->isCollection(), $type->getCollectionKeyTypes(), $type->getCollectionValueTypes());
                }
            }
            $propertyTypes[$propertyName] = PropertyType::createByPropertyInfoTypes($types, $propertyName);
        }
        return $propertyTypes;
    }
}
