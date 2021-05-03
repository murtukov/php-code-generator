<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Argument;
use Murtukov\PHPCodeGenerator\Block;
use Murtukov\PHPCodeGenerator\Func;
use PHPUnit\Framework\TestCase;

class FuncTest extends TestCase
{
    /**
     * @test
     */
    public function emptyBase(): Func
    {
        $func = Func::new('myMethod', 'void');

        $this->expectOutputString(<<<CODE
        function myMethod(): void
        {}
        CODE);

        echo $func;

        return $func;
    }

    /**
     * @test
     * @depends emptyBase
     */
    public function addContent(Func $func): Func
    {
        $func->append('foreach ($users as $user) ', Block::new());

        $this->expectOutputString(<<<'CODE'
        function myMethod(): void
        {
            foreach ($users as $user) {}
        }
        CODE);

        echo $func;

        return $func;
    }

    /**
     * @test
     * @depends addContent
     */
    public function addArguments(Func $func): Func
    {
        $func->createArgument('arg1', SplHeap::class, null)->setNullable();
        $func->createArgument('arg2', 'string', '');
        $func->add(Argument::new('arg3'));

        $this->expectOutputString(<<<'CODE'
        function myMethod(?SplHeap $arg1 = null, string $arg2 = '', $arg3): void
        {
            foreach ($users as $user) {}
        }
        CODE);

        echo $func;

        $this->assertNull($func->getArgument(-10));
        $this->assertNull($func->getArgument(10));

        return $func;
    }
}
