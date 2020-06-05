<?php

declare(strict_types=1);

namespace OOP;

use Murtukov\PHPCodeGenerator\OOP\PhpClass;
use PHPUnit\Framework\TestCase;

class PhpClassTest extends TestCase
{
    /**
     * @test
     */
    public function withDocBlock()
    {
        $phpClass = PhpClass::new('TestClass')
            ->addDocBlock('This is just a test class.');

        $expected = <<<CODE
        /**
         * This is just a test class.
         */
        class TestClass 
        {
        
        }
        CODE;


        $this->assertEquals($expected, (string) $phpClass);
    }
}
