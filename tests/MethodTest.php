<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Argument;
use Murtukov\PHPCodeGenerator\Method;
use Murtukov\PHPCodeGenerator\Instance;
use Murtukov\PHPCodeGenerator\Modifier;
use PHPUnit\Framework\TestCase;

class MethodTest extends TestCase
{
    /**
     * @test
     */
    public function emptyBase()
    {
        $method = Method::new('myMethod', Modifier::PRIVATE, 'void');

        $this->expectOutputString(<<<CODE
        private function myMethod(): void
        {
        
        }
        CODE);

        echo $method;

        return $method;
    }

    /**
     * @test
     * @depends emptyBase
     */
    public function addContent(Method $method)
    {
        $method->append('$object = ', Instance::new(stdClass::class));

        $this->expectOutputString(<<<CODE
        private function myMethod(): void
        {
            \$object = new stdClass();
        }
        CODE);

        echo $method;

        return $method;
    }

    /**
     * @test
     * @depends addContent
     */
    public function addArguments(Method $method)
    {
        $method->createArgument('arg1', SplHeap::class, null)->setNullable();
        $method->createArgument('arg2', 'string', '');
        $method->add(Argument::new('arg3'));

        $this->expectOutputString(<<<CODE
        private function myMethod(?SplHeap \$arg1 = null, string \$arg2 = '', \$arg3): void
        {
            \$object = new stdClass();
        }
        CODE);

        echo $method;

        return $method;
    }
}