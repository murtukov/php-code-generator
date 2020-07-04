<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Utils;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{
    /**
     * @test
     */
    public function stringifyObject()
    {
        $object = new class() {
            public function __toString(): string
            {
                return 'SomeSortOfString';
            }
        };

        $this->assertEquals('"SomeSortOfString"', Utils::stringify($object));

        $this->expectException(Exception::class);

        Utils::stringify(new stdClass());
    }

    /**
     * @test
     */
    public function skipNullValues()
    {
        Utils::$skipNullValues = true;

        $this->assertEquals('', Utils::stringify(null));

        Utils::$skipNullValues = false;
    }
}
