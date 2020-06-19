<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Functions\ArrowFunction;
use Murtukov\PHPCodeGenerator\Functions\Method;
use Murtukov\PHPCodeGenerator\Instance;
use Murtukov\PHPCodeGenerator\Modifier;
use PHPUnit\Framework\TestCase;

class MethodTest extends TestCase
{
    /**
     * @test
     */
    public function methodType()
    {
        $method = Method::new('malaka', Modifier::PRIVATE, 'void');
        $method->append('$object = ', Instance::new(stdClass::class));
        $result = (string) $method;
    }

    /**
     * @test
     */
    public function arrowType()
    {
        $arrow = ArrowFunction::new();
        $arrow->setExpression(Instance::new(stdClass::class));
        $arrow->setStatic();
        $arrow->unsetStatic();
        $result = (string) $arrow;
    }
}