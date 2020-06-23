<?php

declare(strict_types=1);

namespace Functions;

use Murtukov\PHPCodeGenerator\Functions\Argument;
use PHPUnit\Framework\TestCase;
use SplHeap;

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
