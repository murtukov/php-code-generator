<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Mocker;
use PHPUnit\Framework\TestCase;

class MockerTest extends TestCase
{
    /**
     * @test
     */
    public function defaultTest()
    {
        $mock = new Mocker;

        $this->expectOutputString(<<<CODE
        \$lazyConfig->configure(\$config, PhpFile::create('MyFile', []))
        CODE);

        $chain1 = $mock('PhpFile')::create('MyFile', []);
        $chain2 = $mock('$lazyConfig')->configure('$config', $chain1);

        echo $chain2;
    }
}
