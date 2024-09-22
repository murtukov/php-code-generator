<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Argument;
use Murtukov\PHPCodeGenerator\Modifier;
use PHPUnit\Framework\TestCase;

class ArgumentTest extends TestCase
{
    /**
     * @test
     */
    public function fullBase(): Argument
    {
        $argument = Argument::new('arg1', SplHeap::class, null)
            ->setNullable()
            ->setSpread()
            ->setByReference()
        ;

        $this->assertEquals(true, $argument->isSpread());
        $this->assertEquals(true, $argument->isByReference());
        $this->assertEquals('?SplHeap &...$arg1 = null', $argument->generate());

        return $argument;
    }

    /**
     * @test
     *
     * @depends fullBase
     */
    public function removeAttributes(Argument $argument): Argument
    {
        $argument->unsetNullable();
        $argument->unsetByReference();
        $argument->unsetSpread();
        $argument->setDefaultValue(Argument::NO_PARAM);
        $argument->setType('');

        $this->assertEquals('$arg1', $argument->generate());

        return $argument;
    }

    /**
     * @test
     *
     * @depends removeAttributes
     */
    public function makePromoted(Argument $argument): Argument
    {
        $argument->setModifier(Modifier::PRIVATE);
        $argument->setType(SplHeap::class);
        $argument->setNullable();
        $argument->setDefaultValue(null);

        $this->assertEquals('private ?SplHeap $arg1 = null', $argument->generate());

        return $argument;
    }
}
