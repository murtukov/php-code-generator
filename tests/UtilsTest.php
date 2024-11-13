<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Utils;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\BasicEnum;
use Tests\Fixtures\StringBackedEnum;

class UtilsTest extends TestCase
{
    /**
     * @test
     */
    public function stringifyObject(): void
    {
        $object = new class {
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
    public function stringifyEnum(): void
    {
        $this->assertEquals('\\Tests\\Fixtures\\BasicEnum::ONE', Utils::stringify(BasicEnum::ONE));
    }

    /**
     * @test
     */
    public function stringifyBackedEnum(): void
    {
        $this->assertEquals('\\Tests\\Fixtures\\StringBackedEnum::ONE', Utils::stringify(StringBackedEnum::ONE));
    }

    /**
     * @test
     */
    public function skipNullValues(): void
    {
        Utils::$skipNullValues = true;

        $this->assertEquals('', Utils::stringify(null));

        Utils::$skipNullValues = false;
    }
}
