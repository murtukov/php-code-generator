<?php

declare(strict_types=1);

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
        $this->expectOutputString(<<<CODE
        trait Stringifier
        {
            private string \$cache = [];
            protected SplHeap \$heap = null;
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
        $trait->addDocBlock('This is just a test class.');
        $trait->emptyLine();

        $constructor = Method::new('__construct')
            ->append('parent::__construct(...func_get_args())');

        $method = Method::new('getErrors', Modifier::PUBLIC, 'array')
            ->append(
                '// Add here your content...',
                "\n",
                'return ', new Literal('[]')
            );

        $trait->append($constructor);
        $trait->emptyLine();
        $trait->append($method);

        $expected = <<<CODE
        /**
         * This is just a test class.
         */
        trait Stringifier
        {
            private string \$cache = [];
            protected SplHeap \$heap = null;
            
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

        $this->assertEquals($expected, (string) $trait);
    }
}
