<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Modifier;
use Murtukov\PHPCodeGenerator\Property;
use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
    /**
     * @test
     */
    public function createProperty()
    {
        $property = Property::new($name = 'customProperty');

        $this->assertEquals("public $$name", $property->generate());
        $this->assertEquals($name, $property->getName());

        $property->setName($anotherName = 'myProperty');
        $this->assertEquals($anotherName, $property->getName());
        $this->assertEquals(Modifier::PUBLIC, $property->getModifier());

        $property->setStatic();
        $this->assertTrue($property->isStatic);
        $this->assertEquals("public static $$anotherName", $property->generate());

        $property->setPrivate();
        $this->assertEquals("private static $$anotherName", $property->generate());

        $property->setProtected();
        $this->assertEquals("protected static $$anotherName", $property->generate());

        $property->setPublic();
        $property->setDefaultValue(['Yoshimitsu']);
        $this->assertEquals("['Yoshimitsu']", $property->getDefaultValue());
        $this->assertEquals("public static $$anotherName = ['Yoshimitsu']", $property->generate());

        $property->setTypeHint('array');
        $property->setNullable();
        $this->assertTrue($property->isNullable);
        $this->assertEquals("public static ?array $$anotherName = ['Yoshimitsu']", $property->generate());
        $this->assertEquals('array', $property->getTypeHint());

        $property->unsetNullable();
        $property->unsetStatic();
        $property->setConst();
        $this->assertEquals("public const $anotherName = ['Yoshimitsu']", $property->generate());

        $property->unsetConst();
        $this->assertEquals("public array $$anotherName = ['Yoshimitsu']", $property->generate());
    }
}
