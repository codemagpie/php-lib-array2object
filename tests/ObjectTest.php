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

use CodeMagpie\ArrayToObject\ArrayToObjectBuilder;
use CodeMagpie\ArrayToObject\Utils\DataHelper;
use CodeMagpie\ArrayToObjectTests\Stubs\User;
use CodeMagpie\ArrayToObjectTests\Stubs\User1;
use CodeMagpie\ArrayToObjectTests\Stubs\User2;
use CodeMagpie\ArrayToObjectTests\Stubs\User3;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ObjectTest extends TestCase
{
    public function testArrayToObject(): User
    {
        $data = $this->getData();
        $data['child'] = new User($data['child']);
        $user = new User($data);
        self::assertEquals('miss', $user->name);
        self::assertSame('100', $user->age);
        self::assertEquals([1, 2, 3], $user->hobby);
        self::assertEquals('miss', $user->child->name);
        self::assertEquals(null, $user->child->phone);
        self::assertEquals('miss', $user->children[0]->name);
        return $user;
    }

    public function testArrayToLightObject(): User1
    {
        $data = $this->getData();
        $data['child'] = new User1($data['child']);
        $user = new User1($data);
        self::assertEquals('miss', $user->name);
        self::assertSame('100', $user->age);
        self::assertEquals([1, 2, 3], $user->hobby);
        self::assertEquals('miss', $user->child->name);
        self::assertEquals('miss', $user->children[0]->name);
        return $user;
    }

    public function testArrayToObjectBuilder(): void
    {
        $user = new User2();
        ArrayToObjectBuilder::create()->bind($user, $this->getData());
        self::assertEquals('miss', $user->name);
        self::assertSame('100', $user->age);
        self::assertEquals([1, 2, 3], $user->hobby);
        self::assertEquals('miss', $user->child->name);
        self::assertEquals(null, $user->child->phone);
        self::assertEquals('miss', $user->children[0]->name);
    }

    public function testArrayToObjectBuilderFromDocParser(): void
    {
        $user = new User3();
        ArrayToObjectBuilder::createFormPropertyDocParser()->bind($user, $this->getData());
        self::assertEquals('miss', $user->name);
        self::assertSame('100', $user->age);
        self::assertEquals([1, 2, 3], $user->hobby);
        self::assertEquals('miss', $user->child->name);
        self::assertEquals('miss', $user->children[0]->name);
    }

    public function testPerformanceOfArrayToObject(): void
    {
        $list = $this->getList();
        $startAt = microtime(true);
        $start = memory_get_usage(true) / 1000000;
        foreach ($list as $index => $data) {
            $list[$index] = new User($data);
        }
        $end = memory_get_usage(true) / 1000000;
        $endAt = microtime(true);
        self::assertLessThan(0.8, $endAt - $startAt);
        self::assertLessThan(15, $end - $start);
    }

    public function testPerformanceOfArrayToLightObject(): void
    {
        $list = $this->getList();
        $startAt = microtime(true);
        $start = memory_get_usage(true) / 1000000;
        foreach ($list as $index => $data) {
            $list[$index] = new User1($data);
        }
        $end = memory_get_usage(true) / 1000000;
        $endAt = microtime(true);
        self::assertLessThan(0.2, $endAt - $startAt);
        self::assertLessThan(15, $end - $start);
    }

    public function testObjectToArraySimple(): void
    {
        $user = new User2();
        ArrayToObjectBuilder::create()->bind($user, $this->getData());
        self::assertEquals([
            'name' => 'miss',
            'age' => '100',
            'email' => 'test@test.com',
            'hobby' => [1, 2, 3],
            'phone' => '12345678910',
            'address' => 'test',
            'profileInfo' => 'hh',
            'child' => [
                'name' => 'miss',
                'age' => '99',
                'email' => 'test@test.com',
                'address' => 'test',
                'profileInfo' => 'hh',
                'hobby' => [1, 2, 3],
                'phone' => null,
            ],
            'children' => [
                [
                    'name' => 'miss',
                    'age' => '98',
                    'email' => 'test@test.com',
                    'address' => 'test',
                    'profileInfo' => 'hh',
                    'phone' => null,
                    'hobby' => [1, 2, 3],
                ],
                [
                    'name' => 'miss',
                    'age' => '97',
                    'email' => 'test@test.com',
                    'address' => 'test',
                    'profileInfo' => 'hh',
                    'phone' => null,
                    'hobby' => [1, 2, 3],
                ],
            ],
        ], DataHelper::objectToArray($user));
    }

    /**
     * @depends testArrayToObject
     */
    public function testObjectToArray(User $user): void
    {
        self::assertEquals([
            'name' => 'miss',
            'age' => '100',
            'email' => 'test@test.com',
            'hobby' => [1, 2, 3],
            'phone' => '12345678910',
            'address' => 'test',
            'profileInfo' => 'hh',
            'child' => [
                'name' => 'miss',
                'age' => '99',
                'email' => 'test@test.com',
                'address' => 'test',
                'profileInfo' => 'hh',
                'hobby' => [1, 2, 3],
                'phone' => null,
            ],
            'children' => [
                [
                    'name' => 'miss',
                    'age' => '98',
                    'email' => 'test@test.com',
                    'address' => 'test',
                    'profileInfo' => 'hh',
                    'phone' => null,
                    'hobby' => [1, 2, 3],
                ],
                [
                    'name' => 'miss',
                    'age' => '97',
                    'email' => 'test@test.com',
                    'address' => 'test',
                    'profileInfo' => 'hh',
                    'phone' => null,
                    'hobby' => [1, 2, 3],
                ],
            ],
        ], $user->toArray());
    }

    /**
     * @depends testArrayToLightObject
     */
    public function testLightObjectToArray(User1 $user): void
    {
        self::assertEquals([
            'name' => 'miss',
            'age' => '100',
            'email' => 'test@test.com',
            'hobby' => [1, 2, 3],
            'phone' => '12345678910',
            'address' => 'test',
            'profileInfo' => 'hh',
            'child' => [
                'name' => 'miss',
                'age' => '99',
                'email' => 'test@test.com',
                'address' => 'test',
                'profileInfo' => 'hh',
                'hobby' => [1, 2, 3],
            ],
            'children' => [
                [
                    'name' => 'miss',
                    'age' => '98',
                    'email' => 'test@test.com',
                    'address' => 'test',
                    'profileInfo' => 'hh',
                    'hobby' => [1, 2, 3],
                ],
                [
                    'name' => 'miss',
                    'age' => '97',
                    'email' => 'test@test.com',
                    'address' => 'test',
                    'profileInfo' => 'hh',
                    'hobby' => [1, 2, 3],
                ],
            ],
        ], $user->toArray());
    }

    protected function getList()
    {
        $str = file_get_contents(__DIR__ . '/data/user.json');
        return json_decode($str, true, 512, JSON_THROW_ON_ERROR);
    }

    protected function getData(): array
    {
        return [
            'name' => 'miss',
            'age' => 100,
            'email' => 'test@test.com',
            'hobby' => [1, 2, 3],
            'phone' => '12345678910',
            'address' => 'test',
            'profile_info' => 'hh',
            'child' => [
                'name' => 'miss',
                'age' => 99,
                'email' => 'test@test.com',
                'address' => 'test',
                'profile_info' => 'hh',
                'hobby' => [1, 2, 3],
            ],
            'children' => [
                [
                    'name' => 'miss',
                    'age' => 98,
                    'email' => 'test@test.com',
                    'address' => 'test',
                    'profile_info' => 'hh',
                    'hobby' => [1, 2, 3],
                ],
                [
                    'name' => 'miss',
                    'age' => 97,
                    'email' => 'test@test.com',
                    'address' => 'test',
                    'profile_info' => 'hh',
                    'hobby' => [1, 2, 3],
                ],
            ],
        ];
    }
}
