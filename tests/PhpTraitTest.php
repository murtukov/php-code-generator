<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Comment;
use Murtukov\PHPCodeGenerator\Method;
use Murtukov\PHPCodeGenerator\Literal;
use Murtukov\PHPCodeGenerator\Modifier;
use Murtukov\PHPCodeGenerator\PhpTrait;
use PHPUnit\Framework\TestCase;

class PhpTraitTest extends TestCase
{
    /**
     * @test
     */
    public function emptyBase()
    {
        $this->expectOutputString(<<<CODE
        trait Stringifier
        {
        
        }
        CODE);

        $trait = PhpTrait::new('Stringifier');

        echo $trait;

        return $trait;
    }

    /**
     * @test
     * @depends emptyBase
     */
    public function addProperties(PhpTrait $trait)
    {
        $this->expectOutputString(<<<'CODE'
        trait Stringifier
        {
            private string $cache = [];
            protected ?SplHeap $heap = null;
        }
        CODE);

        $trait->addProperty('cache', Modifier::PRIVATE, 'string', []);
        $trait->addProperty('heap', Modifier::PROTECTED, SplHeap::class, null);

        echo $trait;

        return $trait;
    }

    /**
     * @test
     * @depends addProperties
     */
    public function addMethodsAndDocBlock(PhpTrait $trait)
    {
        $trait->setDocBlock('This is just a test class.');
        $trait->emptyLine();

        $constructor = Method::new('__construct')
            ->append('parent::__construct(...func_get_args())');

        $method = Method::new('getErrors', Modifier::PUBLIC, 'array')
            ->append(Comment::slash('Add here your content...'))
            ->append('return ', Literal::new('[]'));

        $trait->append($constructor);
        $trait->emptyLine();
        $trait->append($method);

        $expected = <<<'CODE'
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
                // Add here your content...
                return [];
            }
        }
        CODE;

        $this->assertEquals($expected, $trait->generate());

        return $trait;
    }

    /**
     * @test
     * @depends addMethodsAndDocBlock
     */
    public function modifyTrait(PhpTrait $trait)
    {
        $trait->clearContent();
        $trait->createProperty('anotherProperty');
        $trait->emptyLine();
        $trait->createConstructor();
        $trait->addMethod('anotherMethod');
        $trait->createMethod('createdMethod');

        $this->expectOutputString(<<<'CODE'
        /**
         * This is just a test class.
         */
        trait Stringifier
        {
            public $anotherProperty;
            
            public function __construct()
            {
            
            }
            
            public function anotherMethod()
            {
            
            }
            
            public function createdMethod()
            {
            
            }
        }
        CODE);

        echo $trait;
    }
}
