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
namespace CodeMagpie\ArrayToObject\Constants;

class PropertyModel
{
    /**
     * 自动模式,此模式会根据对象的属性,自动匹配数组key.
     */
    public const AUTO = 'auto';

    /**
     * 此模式不会转换填充数据组key.
     */
    public const NONE = 'none';

    /**
     * 下划线模式,此模式会将填充数组key转换为下划线
     */
    public const UNDERLINE = 'underline';

    /**
     * 小驼峰模式,此模式会将填充数组key转换为小驼峰(默认).
     */
    public const SMALL_HUMP = 'small_hump';
}
