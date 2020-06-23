<?php

declare(strict_types=1);

namespace Functions;

use Murtukov\PHPCodeGenerator\Functions\Argument;
use Murtukov\PHPCodeGenerator\Functions\Method;
use PHPUnit\Framework\TestCase;

class MethodTest extends TestCase
{
    private const TEMPLATE = <<<CODE
    public function %1\$s(%2\$s)
    {
    %3\$s
    }
    CODE;


    /**
     * @test
     */
    public function emptyBase()
    {
        $method = Method::new('myMethod');

        $code = sprintf(self::TEMPLATE, 'myMethod', '', '');

        $this->assertEquals($code, $method->generate());

        return $method;
    }

    /**
     * @test
     * @depends emptyBase
     */
    public function addArguments(Method $method)
    {
        $method->add(Argument::new('arg1', \SplHeap::class, null)->setNullable());

        $code = sprintf(self::TEMPLATE, 'myMethod', '?SplHeap $arg1 = null', '');

        $this->assertEquals($code, $method->generate());
    }
}
