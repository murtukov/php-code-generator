<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Config;
use Murtukov\PHPCodeGenerator\Instance;
use PHPUnit\Framework\TestCase;

class InstanceTest extends TestCase
{
    /**
     * @test
     */
    public function withoutArgs()
    {
        $instance = Instance::new(DateTime::class);

        $this->expectOutputString(<<<CODE
        new DateTime()
        CODE);

        echo $instance;
    }

    /**
     * @test
     */
    public function withOneArg()
    {
        $instance = Instance::new('User', 'Andrew');

        $this->expectOutputString(<<<CODE
        new User('Andrew')
        CODE);

        echo $instance;

        return $instance;
    }

    /**
     * @test
     * @depends withOneArg
     */
    public function addSecondArgument(Instance $instance)
    {
        $instance->addArgument('Jameson');

        $this->expectOutputString(<<<CODE
        new User('Andrew', 'Jameson')
        CODE);

        echo $instance;
    }

    /**
     * @test
     */
    public function shortenNamespace()
    {
        $instance = Instance::new('App\Entity\User');

        $this->expectOutputString(<<<CODE
        new User()
        CODE);

        echo $instance;
    }

    /**
     * @test
     */
    public function suppressShorteningWithCustomSymbol()
    {
        Config::$suppressSymbol = '~';
        $instance = Instance::new('~App\Entity\User');

        $this->expectOutputString(<<<CODE
        new App\Entity\User()
        CODE);

        echo $instance;

        // Reset config
        Config::$suppressSymbol = '@';
    }

    /**
     * @test
     */
    public function suppressShortening()
    {
        $instance = Instance::new('@App\Entity\User');

        $this->expectOutputString(<<<CODE
        new App\Entity\User()
        CODE);

        echo $instance;
    }

    /**
     * @test
     */
    public function differentArgTypes()
    {
        $instance = Instance::multiline('Test', null, [], new Instance('DateTime'), false);

        $result = <<<CODE
        new Test(
            null,
            [],
            new DateTime(),
            false,
        )
        CODE;

        $this->assertEquals($result, $instance->generate());
        $this->assertEquals('new Test(null, [], new DateTime(), false)', $instance->setInline()->generate());
        $this->assertEquals($result, $instance->setMultiline()->generate());

        return $instance;
    }
}
