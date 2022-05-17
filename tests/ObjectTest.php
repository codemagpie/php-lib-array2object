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

use CodeMagpie\ArrayToObjectTests\Stubs\PersonDto;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ObjectTest extends TestCase
{
    public function testArrayToObject1()
    {
        $data = $this->getData();
        $start = memory_get_usage() / 1000000;
        $persons = [];
        for ($i = 0; $i < 20000; ++$i) {
            $persons[] = new PersonDto($data);
        }
        $end = memory_get_usage() / 1000000;
        echo sprintf('memory usage %sm', $end - $start);
        // print memory usage 53.953264m
    }

    public function testArrayToObject2()
    {
        $data = $this->getData();
        $start = memory_get_usage() / 1000000;
        $persons = [];
        for ($i = 0; $i < 20000; ++$i) {
            $persons[] = $this->getObject($data);
        }
        $end = memory_get_usage() / 1000000;
        echo sprintf('memory usage %sm', $end - $start);
        // print memory usage 22.425048m
    }

    public function testCompare()
    {
        $data = $this->getData();
        self::assertEquals(new PersonDto($data), $this->getObject($data));
    }

    protected function getObject($data)
    {
        $temp = new PersonDto([]);
        $temp->age = (int) $data['age'];
        $temp->language = $data['language'];
        $temp->hobby = $data['hobby'];
        $temp->sex = $data['sex'];
        $child = new PersonDto([]);
        $child->age = (int) $data['child']['age'];
        $child->language = $data['child']['language'];
        $child->hobby = $data['child']['hobby'];
        $child->sex = $data['child']['sex'];
        $temp->child = $child;
        $children = [];
        foreach ($data['children'] as $item) {
            $temp1 = new PersonDto([]);
            $temp1->age = (int) $item['age'];
            $temp1->language = $item['language'];
            $temp1->hobby = $item['hobby'];
            $temp1->sex = $item['sex'];
            $children[] = $temp1;
        }
        $temp->children = $children;
        return $temp;
    }

    protected function getData()
    {
        return [
            'age' => '100',
            'language' => 'cn',
            'hobby' => [1, 2, 3],
            'sex' => null,
            'child' => [
                'age' => '100',
                'language' => 1,
                'hobby' => [1, 2, 3],
                'sex' => 'male',
            ],
            'children' => [
                [
                    'age' => '100',
                    'language' => 1,
                    'hobby' => [1, 2, 3],
                    'sex' => 'female',
                ],
                [
                    'age' => '100',
                    'language' => 2,
                    'hobby' => [1, 2, 3],
                    'sex' => 'male',
                ],
            ],
        ];
    }
}
