# PHPCodeGenerator (alpha)
A library to generate any PHP 7.4 code

- [Installation](#installation)
- [Before you start](#before-you-start)
    - [`add` and `create` methods](#add-and-create-methods)
    - [Static initializers]()
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

## Before you start

### `add` and `create` methods
If a component has methods with prefix `add`, it almost always has similar method with prefix `create`.
Both have identical signature and the only difference is, that `add`-methods return `$this` to allow 
[Fluent Interface](#https://en.wikipedia.org/wiki/Fluent_interface):

```php
use Murtukov\PHPCodeGenerator\Functions\Method;

$class
    ->addMethod('__construct')
    ->addMethod('firstMethod', Method::PRIVATE)
    ->addMethod('secondMethod');
```  
while the `create`-methods will return the created object for further modifications:
```php
use Murtukov\PHPCodeGenerator\Functions\Method;

$constructor = $class->createMethod('__construct');
$firstMethod = $class->createMethod('firstMethod', Method::PRIVATE);
$secondMethod = $class->createMethod('secondMethod');

$secondMethod->setStatic();
```

Both approaches offer identical signatures.

### Static initializers
All components have static methods to help initialize the component with specific configuration, e.g.:
```php
use Murtukov\PHPCodeGenerator\Arrays\AssocArray;

# Normal object instantiation
(new AssocArray())
    ->setMultiline()
    ->addItem('name', 'Alfred')
    ->addItem('age', 30);

# You can use static helpers
AssocArray::assoc()
    ->addItem('name', 'Alfred')
    ->addItem('age', 30);
```

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
\Murtukov\PHPCodeGenerator\OOP\PhpClass::
```

## Method

## Object Instantiation

## Array

## DocBlock

## Namespaces

