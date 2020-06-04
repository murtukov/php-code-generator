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

        $expected = <<<CODE
        new DateTime()
        CODE;

        $this->assertEquals($expected, (string) $instance);
    }

    /**
     * @test
     */
    public function withOneArgs()
    {
        $instance = Instance::new(DateTime::class, 'today');

        $expected = <<<CODE
        new DateTime('today')
        CODE;

        $this->assertEquals($expected, (string) $instance);
    }

    /**
     * @test
     */
    public function withMultipleArgs()
    {
        $instance = Instance::new('User', 'Andrew', 'Jameson');

        $expected = <<<CODE
        new User('Andrew', 'Jameson')
        CODE;

        $this->assertEquals($expected, (string) $instance);
    }

    /** @test */
    public function shortenNamespace()
    {
        $instance = Instance::new('App\Entity\User');

        $expected = <<<CODE
        new User()
        CODE;

        $this->assertEquals($expected, (string) $instance);
    }

    /** @test */
    public function suppressShorteningWithCustomSymbol()
    {
        Config::$suppressSymbol = '~';
        $instance = Instance::new('~App\Entity\User');

        $expected = <<<CODE
        new App\Entity\User()
        CODE;

        $this->assertEquals($expected, (string) $instance);

        // Reset config
        Config::$suppressSymbol = '@';
    }

    /** @test */
    public function suppressShortening()
    {
        $instance = Instance::new('@App\Entity\User');

        $expected = <<<CODE
        new App\Entity\User()
        CODE;

        $this->assertEquals($expected, (string) $instance);
    }

    /** @test */
    public function differentArgTypes()
    {
        $instance = Instance::new('Test', null, [], new Instance('DateTime'), false);

        $expected = <<<CODE
        new Test(null, [], new DateTime(), false)
        CODE;

        $this->assertEquals($expected, (string) $instance);
    }
}
