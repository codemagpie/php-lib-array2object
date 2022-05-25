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

use CodeMagpie\ArrayToObject\Utils\PropertyDocParser;
use CodeMagpie\ArrayToObject\Utils\PropertyParser;
use CodeMagpie\ArrayToObjectTests\Stubs\User;
use CodeMagpie\ArrayToObjectTests\Stubs\User1;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class PropertyParserTest extends TestCase
{
    public function testParseType(): void
    {
        $parser = new PropertyParser();
        $propertyTypes = $parser->parseType(User::class);
        self::assertEquals('string', $propertyTypes['name']->getType());
        self::assertEquals(true, $propertyTypes['email']->isMixed());
        self::assertEquals(true, $propertyTypes['phone']->isNullable());
        self::assertEquals('int', $propertyTypes['hobby']->getChild()->getType());
        self::assertEquals(null, $propertyTypes['address']->getChild());
        self::assertEquals(false, $propertyTypes['child']->isNullable());
        self::assertEquals('profileInfo', $propertyTypes['profileInfo']->getPropertyNameHump());
        self::assertEquals('profile_info', $propertyTypes['profileInfo']->getPropertyNameLine());
        self::assertEquals(User::class, $propertyTypes['child']->getClassName());
        self::assertEquals(User::class, $propertyTypes['children']->getChild()->getClassName());
    }

    public function testDocParserType(): void
    {
        $parser = new PropertyDocParser();
        $propertyTypes = $parser->parseType(User1::class);
        self::assertEquals('string', $propertyTypes['name']->getType());
        self::assertEquals(true, $propertyTypes['email']->isMixed());
        self::assertEquals(true, $propertyTypes['phone']->isNullable());
        self::assertEquals('int', $propertyTypes['hobby']->getChild()->getType());
        self::assertEquals(null, $propertyTypes['address']->getChild());
        self::assertEquals(false, $propertyTypes['child']->isNullable());
        self::assertEquals('profileInfo', $propertyTypes['profileInfo']->getPropertyNameHump());
        self::assertEquals('profile_info', $propertyTypes['profileInfo']->getPropertyNameLine());
        self::assertEquals(User1::class, $propertyTypes['child']->getClassName());
        self::assertEquals(User1::class, $propertyTypes['children']->getChild()->getClassName());
    }
}
