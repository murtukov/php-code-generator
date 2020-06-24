<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Argument;
use PHPUnit\Framework\TestCase;

class ArgumentTest extends TestCase
{
    /**
     * @test
     */
    public function emptyBase()
    {
        $argument = Argument::new('arg1', SplHeap::class, null)->setNullable();

        $this->assertEquals('?SplHeap $arg1 = null', $argument->generate());

        return $argument;
    }
}
