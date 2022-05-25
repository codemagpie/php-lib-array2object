# Array To Object
## Introduction
Supports converting array into object and object into array, easy to us
## Installation
```shell
composer require codemagpie/array2object
```
## Usage1
Only need to declare the class property type:
```php
use CodeMagpie\ArrayToObject\AbstractBaseObject

class User extends AbstractBaseObject
{
    public string $name;
    
    public int $age;
    
    public User $child;
    /** 
     * @var User[] 
     */
    public array $children;
}
```
To use:
```php
$arr = [
    'name' => 'test',
    'age' => '17',
    'child' => [
        'name' => 'test1',
        'age' => '17',
    ],
    'children' => [
        [
            'name' => 'test2',
            'age' => '17',
        ],
    ],
];
$user = new User($arr);
// if the user object does not extend AbstractBaseObject, you should use:
// $user = new User();
// \CodeMagpie\ArrayToObject\ArrayToObjectBuilder::create()->bind($user);
// echo \CodeMagpie\ArrayToObject\Utils\DataHelper::objectToArray($user); // Array
echo $user->name; // test
echo $user->age; // 17
echo $user->child->name; // test1
echo $user->children[0]->name; // test2
echo $user->toArray() //Array. if extend ArrayToObjectBuilder
```
## Usage2
Only need declare the property type on the class's annotation:
```php
use CodeMagpie\ArrayToObject\AbstractLightBaseObject

/**
 * @property string $name
 * @property int $age
 * @property User $child
 * @property User[] $children
 */
class User extends AbstractLightBaseObject
{
}
```
To use:
```php
$arr = [
    'name' => 'test',
    'age' => '17',
    'child' => [
        'name' => 'test1',
        'age' => '17',
    ],
    'children' => [
        [
            'name' => 'test2',
            'age' => '17',
        ],
    ],
];
$user = new User($arr);
// if the user object does not extend AbstractLightBaseObject, you should use:
// $user = new User();
// \CodeMagpie\ArrayToObject\ArrayToObjectBuilder::createFormPropertyDocParser()->bind($user);
// echo \CodeMagpie\ArrayToObject\Utils\DataHelper::objectToArray($user);// Array
echo $user->name; // test
echo $user->age; // 17
echo $user->child->name; // test1
echo $user->children[0]->name; // test2
echo $user->toArray() //Array. if extend AbstractLightBaseObject
```
## Usage performance comparison

| proportion    | time consumption | memory usage | memory consumption |
|---------------|------------------|--------------|--------------------|
| usage1:usage2 | 4:1              | 2:3          | ≈ 1:1              |
