# PHP Code Generator Guidelines

## Project Overview
PHP Code Generator is a library designed to programmatically generate PHP 7.4 code. It provides a fluent API for building PHP code structures such as classes, interfaces, traits, functions, methods, and more. The library handles formatting, indentation, and namespace resolution automatically.

## Architecture

### Core Components

1. **File Generation**
   - `PhpFile`: The main entry point for generating complete PHP files with namespaces, use statements, and classes.

2. **OOP Structures**
   - `PhpClass`: Generates PHP class definitions with properties, methods, and constants.
   - `PhpInterface`: Generates PHP interface definitions with method signatures and constants.
   - `PhpTrait`: Generates PHP trait definitions with properties and methods.
   - `OOPStructure`: Base class for OOP structures.

3. **Functions**
   - `AbstractFunction`: Base class for function-like structures.
   - `Func`: Generates standalone PHP functions.
   - `Method`: Generates class methods.
   - `Closure`: Generates PHP closures.
   - `ArrowFunction`: Generates PHP arrow functions.
   - `Signature`: Represents function signatures.
   - `Argument`: Represents function arguments.

4. **Control Structures**
   - `IfElse`: Generates if-else statements.
   - `ElseBlock`: Represents the else block in if-else statements.
   - `ElseIfBlock`: Represents the elseif block in if-else statements.
   - `Loop`: Generates various loop structures (for, foreach, while, do-while).

5. **Utility Components**
   - `Collection`: Handles array generation with various formatting options.
   - `Comment`: Generates different types of comments (block, hash, docblock, slash).
   - `Instance`: Generates object instantiation code.
   - `Literal`: Generates code literally as provided.
   - `Text`: Represents text that should not be processed.
   - `Utils`: Utility functions for stringifying values.

6. **Configuration**
   - `Config`: Global configuration settings for the library.

## Usage Patterns

### Fluent API
The library uses a fluent API pattern, allowing method chaining for building complex structures:

```php
$file = PhpFile::new()
    ->setNamespace('App\Generator')
    ->addUse('App\Entity\User');

$class = $file->createClass('UserManager')
    ->setFinal()
    ->addImplements(JsonSerializable::class);
```

### Component Creation
Components can be created in two ways:

1. **Static factory method**: Using the `new()` static method
   ```php
   $class = PhpClass::new('MyClass');
   ```

2. **Constructor**: Using the `new` keyword
   ```php
   $class = new PhpClass('MyClass');
   ```

### Component Composition
Components can be composed together to build complex structures:

```php
$method = Method::new('convert')
    ->addArgument('value', 'string')
    ->append('return ', Instance::new('Converter')->addArgument('$value'));
```

### Namespace Resolution
The `PhpFile` component automatically resolves class qualifiers from all its child components during rendering:

```php
$file = PhpFile::new();
$class = $file->createClass('MyClass');
$method = $class->createMethod('getConverter');
$method->append('return ', Instance::new('App\Service\Converter'));
```

This will automatically add `use App\Service\Converter;` to the file.

## Best Practices

1. **Use the Fluent API**
   - Chain methods together for cleaner, more readable code.

2. **Avoid String Concatenation**
   - Instead of `$method->append('return ' . Instance::new('App\MyClass'))`, use `$method->append('return ', Instance::new('App\MyClass'))`.

3. **Use Appropriate Collection Types**
   - Use `Collection::assoc()` for associative arrays.
   - Use `Collection::numeric()` for sequential arrays.

4. **Leverage Automatic Namespace Resolution**
   - Let `PhpFile` handle the use statements by using proper component objects.

5. **Use Multiline When Appropriate**
   - For complex arrays or function signatures, use `setMultiline()` for better readability.

6. **Use Conditional Methods**
   - Use methods like `addIfNotEmpty()`, `addIfNotNull()`, etc., for conditional code generation.

7. **Organize Code Generation**
   - Build complex structures step by step, using variables to store intermediate components.

8. **Use Comments**
   - Add appropriate comments to generated code using the `Comment` class.

## Global Configuration

The library provides global configuration options through the `Config` class:

1. **Indentation**
   ```php
   Config::$indent = '    '; // Default is 4 spaces
   ```

2. **Qualifier Shortening**
   ```php
   Config::$shortenQualifiers = true; // Default is true
   ```

3. **Suppress Symbol**
   ```php
   Config::$suppressSymbol = '@'; // Default is @
   ```

## Testing

The library includes comprehensive unit tests for all components. To run the tests:

```bash
php ./vendor/phpunit/phpunit/phpunit ./tests
```

## Limitations

1. The library generates PHP 7.4 code and may not support newer PHP features.
2. Class qualifiers are not resolved from scalars and arrays unless wrapped in special objects.
3. The library does not validate the generated code for syntax errors.