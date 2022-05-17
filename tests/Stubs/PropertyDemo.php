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

class PropertyDemo
{
    public $aaa;

    /**
     * @var int|string
     */
    public $age;

    /**
     * @var string
     */
    public $email;

    public array $address;

    /**
     * @var string[]
     */
    public array $hobby;

    public string $name;

    public ?string $phone;

    public PropertyDemo $child;

    /**
     * @var PropertyDemo[]
     */
    public array $children;
}
