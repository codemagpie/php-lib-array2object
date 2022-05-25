<?php

namespace CodeMagpie\ArrayToObjectTests\Stubs;

/**
 * @property string $name
 * @property string $age
 * @property mixed $email
 * @property string $address
 * @property string $profileInfo
 * @property ?string $phone
 * @property User3 $child
 * @property User3[] $children
 */
class User3
{
    use User1Trait;
}