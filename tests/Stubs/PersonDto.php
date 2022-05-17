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
namespace CodeMagpie\ArrayToObjectTests\Stubs;

use CodeMagpie\ArrayToObject\AbstractBaseObject;

class PersonDto extends AbstractBaseObject
{
    public int $age;

    /**
     * @var mixed
     */
    public $language;

    public ?string $sex;

    /**
     * @var string[]
     */
    public array $hobby;

    public ?PersonDto $child = null;

    /**
     * @var PersonDto[]
     */
    public array $children = [];
}
