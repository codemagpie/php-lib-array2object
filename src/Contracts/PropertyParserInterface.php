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
namespace CodeMagpie\ArrayToObject\Contracts;

use CodeMagpie\ArrayToObject\PropertyType;

interface PropertyParserInterface
{
    /**
     * @return array<string,PropertyType>
     */
    public function parseType(string $className): array;
}
