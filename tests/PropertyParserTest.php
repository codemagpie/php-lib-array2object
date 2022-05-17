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
namespace CodeMagpie\ArrayToObjectTests;

use CodeMagpie\ArrayToObject\Utils\PropertyParser;
use CodeMagpie\ArrayToObjectTests\Stubs\PropertyDemo;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class PropertyParserTest extends TestCase
{
    public function testParseType(): void
    {
        $parser = new PropertyParser(PropertyDemo::class);
        $propertyTypes = $parser->parseType();
        self::assertArrayNotHasKey('age', $propertyTypes);
        self::assertEquals('string', $propertyTypes['name']->type);
        self::assertEquals('string', $propertyTypes['email']->type);
        self::assertEquals(true, $propertyTypes['phone']->nullable);
        self::assertEquals('string', $propertyTypes['hobby']->child->type);
        self::assertEquals(null, $propertyTypes['address']->child);
        self::assertEquals(false, $propertyTypes['child']->nullable);
        self::assertEquals(PropertyDemo::class, $propertyTypes['child']->className);
        self::assertEquals(PropertyDemo::class, $propertyTypes['children']->child->className);
    }

    public function test111()
    {
        $a = ['name' => null];
        var_dump(array_key_exists('name', $a));
    }
}
