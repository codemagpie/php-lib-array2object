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

use CodeMagpie\ArrayToObject\AbstractLightBaseObject;

/**
 * @property string $name
 * @property string $age
 * @property mixed $email
 * @property string $address
 * @property string $profileInfo
 * @property ?string $phone
 * @property User1 $child
 * @property User1[] $children
 */
class User1 extends AbstractLightBaseObject
{
    use User1Trait;
}
