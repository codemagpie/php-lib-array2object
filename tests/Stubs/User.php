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

class User extends AbstractBaseObject
{
    public string $name;

    public string $age;

    /**
     * @var mixed
     */
    public $email;

    public string $address;

    public string $profileInfo;

    /**
     * @var int[]
     */
    public array $hobby;

    public ?string $phone = null;

    public User $child;

    /**
     * @var User[]
     */
    public array $children;
}
