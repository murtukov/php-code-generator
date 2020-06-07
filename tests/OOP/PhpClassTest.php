<?php

declare(strict_types=1);

namespace OOP;

use ArrayAccess;
use Exception;
use JsonSerializable;
use Murtukov\PHPCodeGenerator\Functions\Method;
use Murtukov\PHPCodeGenerator\Literal;
use Murtukov\PHPCodeGenerator\OOP\PhpClass;
use Murtukov\PHPCodeGenerator\OOP\Property;
use PHPUnit\Framework\TestCase;

class PhpClassTest extends TestCase
{
    /**
     * @test
     */
    public function fullBuild()
    {
        $class = PhpClass::new('MyException')
            ->addConst('KNOWN_TYPES', ['DYNAMIC', 'STATIC'], Property::PRIVATE)
            ->addProperty('errors', Property::PRIVATE, '', [])
            ->addImplements(JsonSerializable::class, ArrayAccess::class)
            ->setExtends(Exception::class)
            ->setFinal()
            ->addDocBlock('This is just a test class.');

        $constructor = $class->createConstructor();
        $constructor->append(new Literal('parent::__construct(...func_get_args())'));

        $method = $class->createMethod('getErrors', Method::PUBLIC, 'array');
        $method->append(
            '// Add here your content...',
            "\n",
            'return ', new Literal('[]')
        );

        $expected = <<<CODE
        /**
         * This is just a test class.
         */
        final class MyException extends Exception implements JsonSerializable, ArrayAccess
        {
            private const KNOWN_TYPES = ['DYNAMIC', 'STATIC'];
            private \$errors = [];
            
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
        CODE;

        $this->assertEquals($expected, (string) $class);
    }
}
