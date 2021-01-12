<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Argument;
use PHPUnit\Framework\TestCase;

class ArgumentTest extends TestCase
{
    /**
     * @test
     */
    public function emptyBase(): Argument
    {
        $argument = Argument::new('arg1', SplHeap::class, null)
            ->setNullable()
            ->setSpread()
            ->setByReference()
        ;

        $this->assertEquals(true, $argument->isSpread());
        $this->assertEquals(true, $argument->isByReference());
        $this->assertEquals('?SplHeap &...$arg1 = null', $argument->generate());

        $argument->unsetNullable();
        $argument->unsetByReference();
        $argument->unsetSpread();
        $argument->setDefaultValue(Argument::NO_PARAM);
        $argument->setType('');

        $this->assertEquals('$arg1', $argument->generate());

        return $argument;
    }
}
