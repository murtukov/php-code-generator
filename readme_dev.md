# PHPCodeGenerator (alpha)
A library to generate any PHP 7.4 code

- [Installation](#installation)
- [File](#file)
- OOP
    - [Class](#class)
    - [Interface](#interface)
    - [Trait](#trait)
- Functions
    - [Function](#function)
    - [Method](#method)
    - [Closure](#closure)
    - [Arrow](#arrow)
- [Object Instantiation](#object-instantiation)
- [Array](#array)
    - Inline array
    - Multiline array
    - Map
- Control structures
    - IfElse
    - Loops
        - while
        - for
        - foreach
        - do-while
- [DocBlock](#docblock)
- [Namespaces](#namespaces)


## File
```php
use Murtukov\PHPCodeGenerator\PhpFile;

$file = PhpFile::new()->setNamespace('App\Generator');

$class = $file->createClass('MyClass');
$class->setExtends('App\My\BaseClass')
    ->addImplements(Traversable::class, JsonSerializable::class)
    ->setFinal()
    ->addDocBlock("This file was generated and shouldn't be modified")
    ->addConstructor();

echo $file;
```
Result:
```php
<?php

namespace App\Generator;

use App\My\BaseClass;

/**
 * This file was generated and shouldn't be modified
 */
final class MyClass extends BaseClass implements Traversable, JsonSerializable
{
    public function __construct()
    {
    
    }
}
```
## Class
```php
use Murtukov\PHPCodeGenerator\Comment;use Murtukov\PHPCodeGenerator\Literal;
use Murtukov\PHPCodeGenerator\Method;
use Murtukov\PHPCodeGenerator\Modifier;
use Murtukov\PHPCodeGenerator\PhpClass;

$class = PhpClass::new('Stringifier')
    ->addConst('KNOWN_TYPES', ['DYNAMIC', 'STATIC'], Modifier::PRIVATE)
    ->addProperty('errors', Modifier::PRIVATE, '', [])
    ->emptyLine()
    ->addImplements(JsonSerializable::class, ArrayAccess::class)
    ->setExtends(Exception::class)
    ->setFinal()
    ->addDocBlock('This is just a test class.');

$class->createConstructor()
    ->append('parent::__construct(...func_get_args())');

$class->emptyLine();

# Create a method separatly
$method = Method::new('getErrors', Modifier::PUBLIC, 'array')
    ->append(Comment::slash('Add here your content...'))
    ->append('return ', new Literal('[]'))
;

$class->append($method);

echo $class;
```
Result:
```php
/**
 * This is just a test class.
 */
final class Stringifier extends Exception implements JsonSerializable, ArrayAccess
{
    private const KNOWN_TYPES = ['DYNAMIC', 'STATIC'];
    private $errors = [];
    
    public function __construct()
    {
        parent::__construct(...func_get_args());
    }
    
    public function getErrors(): array
    {
        // Add here your content...
        return [];
    }
}
```

## Interface
```php
use Murtukov\PHPCodeGenerator\BlockInterface;
use Murtukov\PHPCodeGenerator\ConverterInterface;
use Murtukov\PHPCodeGenerator\Method;
use Murtukov\PHPCodeGenerator\Modifier;
use Murtukov\PHPCodeGenerator\PhpInterface;


$interface = PhpInterface::new('StringifierInterface')
    ->addExtends(BlockInterface::class, ConverterInterface::class)
    ->addConst('NAME', 'MyInterface')
    ->addConst('TYPE', 'Component')
    ->emptyLine()
    ->addSignature('parse', 'string');

$interface->emptyLine();

$stringifyMethod = $interface->createSignature('stringify', 'string');
$stringifyMethod->addArgument('escapeSlashes', 'bool', false);
$stringifyMethod->addDocBlock('Convert value to string.');

$interface->emptyLine();

$dumpMethod = Method::new('dump', Modifier::PUBLIC, 'string');
$interface->addSignatureFromMethod($dumpMethod);

echo $interface;
```
Result:
```php
interface StringifierInterface extends BlockInterface, ConverterInterface
{
    public const NAME = 'MyInterface';
    public const TYPE = 'Component';
    
    public function parse(): string;

    /**
     * Convert value to string.
     */
    public function stringify(bool $escapeSlashes = false): string;

    public function dump(): string;
}
```

## Trait
```php
use Murtukov\PHPCodeGenerator\Comment;
use Murtukov\PHPCodeGenerator\Literal;
use Murtukov\PHPCodeGenerator\Method;
use Murtukov\PHPCodeGenerator\Modifier;
use Murtukov\PHPCodeGenerator\PhpTrait;

$trait = PhpTrait::new('Stringifier')
    ->addProperty('cache', Modifier::PRIVATE, 'string', [])
    ->addProperty('heap', Modifier::PROTECTED, SplHeap::class, null)
    ->addDocBlock('This is just a test class.')
    ->emptyLine();

$constructor = Method::new('__construct')
    ->append('parent::__construct(...func_get_args())');

$method = Method::new('getErrors', Modifier::PUBLIC, 'array')
    ->append(Comment::hash('Add your content here...'))
    ->append('return ', new Literal('[]'));

$trait->append($constructor);
$trait->emptyLine();
$trait->append($method);

echo $trait;
```
Result:
```php
/**
 * This is just a test class.
 */
trait Stringifier
{
    private string $cache = [];
    protected ?SplHeap $heap = null;
    
    public function __construct()
    {
        parent::__construct(...func_get_args());
    }
    
    public function getErrors(): array
    {
        # Add your content here...
        return [];
    }
}
```

## Method
```php
use Murtukov\PHPCodeGenerator\Argument;
use Murtukov\PHPCodeGenerator\Instance;
use Murtukov\PHPCodeGenerator\Method;
use Murtukov\PHPCodeGenerator\Modifier;

$method = Method::new('myMethod', Modifier::PRIVATE, 'void');
$method->append('$object = ', Instance::new(stdClass::class));
$method->createArgument('arg1', SplHeap::class, null)->setNullable();
$method->createArgument('arg2', 'string', '');
$method->add(Argument::new('arg3'));

echo $method;
```
Result:
```php
private function myMethod(?SplHeap $arg1 = null, string $arg2 = '', \$arg3): void
{
    $object = new stdClass();
}
```

## Object Instantiation

## Array

## DocBlock

## Namespaces

