<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Literal;
use Murtukov\PHPCodeGenerator\Method;
use Murtukov\PHPCodeGenerator\Modifier;
use Murtukov\PHPCodeGenerator\PhpClass;
use PHPUnit\Framework\TestCase;

class PhpClassTest extends TestCase
{
    /**
     * @test
     */
    public function emptyBase()
    {
        $code = <<<CODE
        class Stringifier
        {
        
        }
        CODE;

        $class = PhpClass::new('Stringifier');
        $this->assertEquals($code, $class->generate());

        return $class;
    }

    /**
     * @test
     * @depends emptyBase
     */
    public function addExtend(PhpClass $class)
    {
        $code = <<<CODE
        class Stringifier extends SplStack
        {
        
        }
        CODE;

        $class->setExtends(SplStack::class);
        $this->assertEquals($code, $class->generate());

        return $class;
    }

    /**
     * @test
     * @depends addExtend
     */
    public function addImplements(PhpClass $class)
    {
        $code = <<<CODE
        class Stringifier extends SplStack implements JsonSerializable, ArrayAccessible
        {
        
        }
        CODE;

        $class->addImplements(JsonSerializable::class, ArrayAccessible::class);
        $this->assertEquals($code, $class->generate());

        return $class;
    }

    /**
     * @test
     * @depends addImplements
     */
    public function addProperties(PhpClass $class)
    {
        $code = <<<'CODE'
        class Stringifier extends SplStack implements JsonSerializable, ArrayAccessible
        {
            public const NAME = 'MyStringifier';
            public const TYPE = 'ObjectStringifier';
            
            private string $cache = [];
            protected ?SplHeap $heap = null;
        }
        CODE;

        $class->addConst('NAME', 'MyStringifier');
        $class->addConst('TYPE', 'ObjectStringifier');
        $class->emptyLine();
        $class->addProperty('cache', Modifier::PRIVATE, 'string', []);
        $class->addProperty('heap', Modifier::PROTECTED, SplHeap::class, null);

        $this->assertEquals($code, $class->generate());

        return $class;
    }

    /**
     * @test
     */
    public function fullBuild()
    {
        $class = PhpClass::new('MyClass')
            ->addConst('KNOWN_TYPES', ['DYNAMIC', 'STATIC'], Modifier::PRIVATE)
            ->addProperty('errors', Modifier::PRIVATE, '', [])
            ->emptyLine()
            ->addImplements(JsonSerializable::class, ArrayAccess::class)
            ->setExtends(Exception::class)
            ->setFinal()
            ->setDocBlock('This is just a test class.');

        $class->setName('Stringifier');

        $constructor = Method::new('__construct')
            ->append('parent::__construct(...func_get_args())');

        $method = Method::new('getErrors', Modifier::PUBLIC, 'array')
            ->append(
                '// Add here your content...',
                "\n",
                'return ', new Literal('[]')
            );

        $class->append($constructor);
        $class->emptyLine();
        $class->append($method);

        $docBlock = $class->getDocBlock();

        $this->assertEquals(
            <<<CODE
            /**
             * This is just a test class.
             */
            CODE,
            $docBlock->generate()
        );

        $this->assertTrue($class->isFinal());

        $expected = <<<'CODE'
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
        CODE;

        $this->assertEquals($expected, (string) $class);

        return $class;
    }

    /**
     * @test
     * @depends fullBuild
     */
    public function removeParts(PhpClass $class)
    {
        $class
            ->unsetFinal()
            ->removeDocBlock()
            ->setExtends('')
            ->removeImplements()
            ->clearContent();

        $this->expectOutputString(<<<CODE
        class Stringifier
        {
        
        }
        CODE);

        echo $class;

        return $class;
    }

    /**
     * @test
     * @depends removeParts
     */
    public function addAnotherParts(PhpClass $class)
    {
        $class->setAbstract();
        $class->createDocBlock()
            ->addLine('This is a new class!')
            ->addEmptyLine()
            ->addLine('Endline...');

        $class->addMethod('myCustomMethod');

        $this->assertTrue($class->isAbstract());
        $class->unsetAbstract();

        $this->expectOutputString(<<<CODE
        /**
         * This is a new class!
         * 
         * Endline...
         */
        class Stringifier
        {
            public function myCustomMethod()
            {
            
            }
        }
        CODE);

        echo $class;
    }
}
