<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Argument;
use Murtukov\PHPCodeGenerator\Func;
use Murtukov\PHPCodeGenerator\Instance;
use PHPUnit\Framework\TestCase;

class FuncTest extends TestCase
{
    /**
     * @test
     */
    public function emptyBase()
    {
        $func = Func::new('myMethod', 'void');

        $this->expectOutputString(<<<CODE
        function myMethod(): void
        {
        
        }
        CODE);

        echo $func;

        return $func;
    }

    /**
     * @test
     * @depends emptyBase
     */
    public function addContent(Func $func)
    {
        $func->append('$object = ', Instance::new(stdClass::class));

        $this->expectOutputString(<<<CODE
        function myMethod(): void
        {
            \$object = new stdClass();
        }
        CODE);

        echo $func;

        return $func;
    }

    /**
     * @test
     * @depends addContent
     */
    public function addArguments(Func $func)
    {
        $func->createArgument('arg1', SplHeap::class, null)->setNullable();
        $func->createArgument('arg2', 'string', '');
        $func->add(Argument::new('arg3'));

        $this->expectOutputString(<<<CODE
        function myMethod(?SplHeap \$arg1 = null, string \$arg2 = '', \$arg3): void
        {
            \$object = new stdClass();
        }
        CODE);

        echo $func;

        return $func;
    }
}