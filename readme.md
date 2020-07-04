# PHPCodeGenerator
A library to generate PHP 7.4 code

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/murtukov/PHPCodeGenerator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/murtukov/PHPCodeGenerator/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/murtukov/PHPCodeGenerator/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/murtukov/PHPCodeGenerator/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/murtukov/PHPCodeGenerator/badges/build.png?b=master)](https://scrutinizer-ci.com/g/murtukov/PHPCodeGenerator/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/murtukov/PHPCodeGenerator/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

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
    - [Arrow Function](#arrow-function)
- [Object Instantiation](#object-instantiation)
- [Arrays](#arrays)
- Control structures
    - [IfElse](#if--else)
    - [Loops](#loops)
- [Comments](#comments)
- [Namespaces](#namespaces)
- [Global Configs](#global-configs)

## Installation
```
composer require murtukov/php-code-generator
```

# Components

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
use Murtukov\PHPCodeGenerator\Comment;
use Murtukov\PHPCodeGenerator\Literal;
use Murtukov\PHPCodeGenerator\Method;
use Murtukov\PHPCodeGenerator\Modifier;
use Murtukov\PHPCodeGenerator\PhpClass;

$class = PhpClass::new('Stringifier')
    ->addConst('KNOWN_TYPES', ['DYNAMIC', 'STATIC'], Modifier::PRIVATE)
    ->addProperty('errors', Modifier::PRIVATE, '', [])
    ->addImplements(JsonSerializable::class, ArrayAccess::class)
    ->setExtends(Exception::class)
    ->setFinal()
    ->addDocBlock('This is just a test class.');

$class->emptyLine();

$class->createConstructor()
    ->append('parent::__construct(...func_get_args())');

$class->emptyLine();

# Create a method separately
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

$signature = $interface->createSignature('stringify', 'string');
$signature->addArgument('escapeSlashes', 'bool', false);
$signature->addDocBlock('Convert value to string.');

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
    ->addProperty('cache', Modifier::PRIVATE, 'array', [])
    ->addProperty('heap', Modifier::PROTECTED, SplHeap::class, null)
    ->addDocBlock('This is just a test trait.')
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
    private array $cache = [];
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

## Function
```php
use Murtukov\PHPCodeGenerator\Argument;
use Murtukov\PHPCodeGenerator\Instance;
use Murtukov\PHPCodeGenerator\Func;

$func = Func::new('myMethod', 'void');

# Crete argument and return it
$func->createArgument('arg1', SplHeap::class, null)->setNullable();

# Create argument and return function
$func->addArgument('arg2', 'string', '');

# Adding argument from object
$func->add(Argument::new('arg3'));

# Add content
$func->append('$object = ', Instance::new(stdClass::class));

echo $func;
```
Result:
```php
function myMethod(?SplHeap $arg1 = null, string $arg2 = '', $arg3): void
{
    $object = new stdClass();
}
```


## Method
```php
use Murtukov\PHPCodeGenerator\Argument;
use Murtukov\PHPCodeGenerator\Instance;
use Murtukov\PHPCodeGenerator\Method;
use Murtukov\PHPCodeGenerator\Modifier;

$method = Method::new('myMethod', Modifier::PRIVATE, 'void');

# Crete argument and return it
$method->createArgument('arg1', SplHeap::class, null)->setNullable();

# Create argument and return function
$method->addArgument('arg2', 'string', '');

# Adding argument from object
$method->add(Argument::new('arg3'));

# Add content
$method->append('$object = ', Instance::new(stdClass::class));

echo $method;
```
Result:
```php
private function myMethod(?SplHeap $arg1 = null, string $arg2 = '', $arg3): void
{
    $object = new stdClass();
}
```

## Closure
```php
use Murtukov\PHPCodeGenerator\Argument;
use Murtukov\PHPCodeGenerator\Loop;

# Create closure with 'array' return type
$closure = Closure::new('array');

# Create argument
$closure->addArgument('value');

# Create argument and return it
$closure->createArgument('options')
    ->setType('array')
    ->setDefaultValue([]);

# Create argument from object
$arg = Argument::new('filter', 'bool', false);
$closure->add($arg);

# Add uses of external variables
$closure->bindVar('this');
# by reference
$closure->bindVar('global', true);

# Create foreach loop
$foreach = Loop::foreach('$options as &$option')
    ->append('unset($option)');

# Append foreach as content of the closure
$closure->append($foreach);
```
Result:
```php
function ($value, array $options = [], bool $filter = false) use ($this, &$global): array {
    foreach ($options as &$option) {
        unset($option);
    }
}
```


## Arrow Function
```php
$arrow = ArrowFunction::new([
    'name' => 'Alrik',
    'age' => 30
]);

$arrow->setStatic();

echo $arrow;
```
Result:
```php
static fn() => [
    'name' => 'Alrik',
    'age' => 30,
]
```


## Object Instantiation
```php
use Murtukov\PHPCodeGenerator\Instance;

# Create an instance with a single argument
$instance = Instance::new('App\Entity\DateTime', '2000-01-01');

# Add a second argument
$instance->addArgument(null);

echo $instance;
```
Result:
```php
new DateTime('2000-01-01', null);
```
You can prevent shortening of the class qualifier by prefixing its name with `@` symbol.
```php
$instance = Instance::new('@App\Entity\DateTime', '2000-01-01');
```
Result:
```php
new App\Entity\DateTime('2000-01-01', null);
```
The `@` suppress symbol can by changed with static config class.
```php
use Murtukov\PHPCodeGenerator\Config;

Config::$suppressSymbol = '~';
```

## Arrays
This library provides a useful tool to stringify variables: `Utils::stringify()`. It is similar to 
the `var_export` function, but with more control over arrays formatting. Arrays can also be wrapped 
by the `Collection` class, which internally use `Utils::stringify()`.

Arrays with `0`-key defined are considered "numeric" and are stringified inline and without keys by default.
```php
use Murtukov\PHPCodeGenerator\Utils;

echo Utils::stringify(['Test', 100, 5.67, array(), true, NULL]);
```
Result:
```php
['Test', 100, 5.67, [], true, null]
```
All other arrays are stringified multiline and with keys:
```php
use Murtukov\PHPCodeGenerator\Utils;

echo Utils::stringify(['name' => 'Justin', 'age' => 25]);
```
Result:
```php
[
    'name' => 'Justin', 
    'age' => 25
]
```
If you want to define yourself, how arrays are stringified, simply wrap them into `Collection` class:
```php
use Murtukov\PHPCodeGenerator\Collection;

echo Collection::numeric(['name' => 'Justin', 'age' => 25])
    ->setMultiline();
```
Result:
```php
[
    'Justin',
    25,
]
```
`assoc` collection example:
```php
use Murtukov\PHPCodeGenerator\Collection;

echo Collection::assoc(['Tim', 'Max', 'Alfred']);
```
Result:
```php
[
    0 => 'Tim', 
    1 => 'Max', 
    2 => 'Alfred'
]
```
The `Collection` class applies its formatting rules only to the top level array, using default formatting for
all nested arrays:
```php
use Murtukov\PHPCodeGenerator\Collection;

echo Collection::numeric(['apple', 'banana', ['strawberry', 'tomato']])
    ->setMultiline();
```
Result:
```php
[
    'apple',
    'banana',
    ['strawberry', 'tomato'],
]
```
## If ... else
```php
use Murtukov\PHPCodeGenerator\IfElse;
use Murtukov\PHPCodeGenerator\Text;

echo IfElse::new('$name === 15')
    ->append('$names = ', "['name' => 'Timur']")
    ->createElseIf(new Text('$name === 95'))
        ->append('return null')
    ->end()
    ->createElseIf('$name === 95')
        ->append('return null')
    ->end()
    ->createElse()
        ->append('$x = 95')
        ->append('return false')
    ->end();
```
Result:
```php
if ($name === 15) {
    $names = ['name' => 'Timur'];  
} elseif ('\$name === 95') {
    return null;
} elseif ($name === 95) {
    return null;
} else {
    $x = 95;
    return false;
}
```

## Loops
```php
use Murtukov\PHPCodeGenerator\Comment;
use Murtukov\PHPCodeGenerator\Loop;

echo Loop::for('$i = 1; $i < 1000; ++$i')
    ->append('$x = $i')
    ->emptyLine()
    ->append(Comment::hash('Ok, stop now...'))
    ->append('break');

echo Loop::foreach('$apples as $apple')
    ->append('$x = $apple')
    ->append('continue');


echo Loop::while('true')
    ->append('$x = $i')
    ->append('break');


echo Loop::doWhile('true')
    ->append(Comment::block('Hello, World!'));
```
Results:
```php
for ($i = 1; $i < 1000; ++$i) {
    $x = $i;
    
    # Ok, stop now...
    break;
}

foreach ($apples as $apple) {
    $x = $apple;
    continue;
}

while (true) {
    $x = $i;
    break;
}

do {
    /*
     * Hello, World!
     */
} while (true)
```

## Comments
```php
use Murtukov\PHPCodeGenerator\Comment;

echo Comment::block('Hello, World!');
echo Comment::hash('Hello, World!');
echo Comment::docBlock('Hello, World!');
echo Comment::slash('Hello, World!');
```
Result:
```php
/*
 * Hello, World!
 */

# Hello, World!

/**
 * Hello, World!
 */

// Hello, World!
```

## Namespaces
`PhpFile` component automatically resolves class qualifiers from all its child components during the rendering.

```php
use Murtukov\PHPCodeGenerator\Collection;
use Murtukov\PHPCodeGenerator\Instance;
use Murtukov\PHPCodeGenerator\PhpFile;

$file = PhpFile::new();

$class = $file->createClass('MyClass');
$construct = $class->createConstructor();

$array = Collection::numeric()
    ->push(Instance::new('App\Service\Converter'))
    ->push(Instance::new('App\Service\Normalizer'));

$construct->append('return ', $array);

echo $file;
```
Result:
```php
<?php

use App\Service\Converter;
use App\Service\Normalizer;

class MyClass
{
    public function __construct()
    {
        return [new Converter(), new Normalizer()];
    }
}
```

However class qualifiers are NOT resolved from scalaras and arrays, unless wrapped in special objects:
```php
use Murtukov\PHPCodeGenerator\Collection;
use Murtukov\PHPCodeGenerator\Literal;
use Murtukov\PHPCodeGenerator\PhpFile;

$file = PhpFile::new();

$class = $file->createClass('MyClass');
$construct = $class->createConstructor();


$array = Collection::numeric();

# This qualifiers are not resolved automatically
$array
    ->push(Literal::new('new App\Service\Converter()'))
    ->push('new App\Service\Normalizer()');

$construct->append('return ', $array);
```
Result:
```php
<?php

class MyClass
{
    public function __construct()
    {
        return [new App\Service\Converter(), 'new App\Service\Normalizer()'];
    }
}
```
You can always add use statements manually by calling `$file->addUse()` or `$file->addUseGroup()`:
```php
$file->addUse('App\Entity\User');
$file->addUse('App\Service\UserManager', 'Manager');
$file->addUseGroups('Symfony\Validator\Converters', 'NotNull', 'Length', 'Range');
```
Result:
```php
use App\Entity\User;
use App\Service\UserManager as Manager;
use Symfony\Validator\Converters\{NotNull, Length, Range};
```

Although all components if this library implement the magic `__toString()` method, avoid concatenating
them, as it will convert them into string scalars and all class qualifiers will be lost.

So instead of concatenation:
```php
$method->append('return ' . Instance::new('App\MyClass'));
```
pass parts as separate arguments, as `append` is a [variadic function](#https://www.php.net/manual/en/migration56.new-features.php#migration56.new-features.variadics):
```php
$method->append('return ', Instance::new('App\MyClass'));
```

## Global Configs
All global configs are stored as static properties in the `Config` class.

#### Indent
Default indent contains 4 spaces. You can change it by rewriting the `$indent` property:
```php
use Murtukov\PHPCodeGenerator\Config;

Config::$indent = '  ';
```

#### Shorten qualifiers
As mentioned in the [Namespaces](#namespaces) section, class qualifiers are shortened automatically and added
to the top of `PhpFile` output. In order to disable it overwrite the `$shortenQualifiers` property:
```php
use Murtukov\PHPCodeGenerator\Config;

Config::$shortenQualifiers = false;
```

#### Suppress symbol
As mentioned in the [Object Instantiation](#object-instantiation) section, you can suppress shortening of
class qualifiers by prefixing them with the `@` symbol. In order to change this symbol, overwrite the
`$suppressSymbol` property:
```php
use Murtukov\PHPCodeGenerator\Config;

Config::$suppressSymbol = '%';
```