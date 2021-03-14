<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Literal;
use PHPUnit\Framework\TestCase;

class LiteralTest extends TestCase
{
    public function testWithoutValues(): void
    {
        $expected = '$foo = "bar";';
        $literal = new Literal($expected);
        $this->assertSame($expected, $literal->generate());
    }

    public function testWithValues(): void
    {
        $literal = new Literal(
            '$foo = %s; %s',
            new Literal('"bar"'),
            new Literal('echo $foo;')
        );
        $this->assertSame('$foo = "bar"; echo $foo;', $literal->generate(), );
    }

    public function testWithValuesAndProtectedPlaceholders(): void
    {
        $literal = new Literal(
            '$foo = %s; sprintf("This value should not be quote %%s.", %s);',
            new Literal('"bar"'),
            new Literal('$foo')
        );
        $this->assertSame(
            '$foo = "bar"; sprintf("This value should not be quote %s.", $foo);',
            $literal->generate()
        );
    }

    public function testWithValuesAndProtectedPlaceholdersWithoutValues(): void
    {
        $literal = new Literal(
            'sprintf("This value should not be quote %%s.", \'foo\');'
        );
        $this->assertSame(
            'sprintf("This value should not be quote %s.", \'foo\');',
            $literal->generate()
        );
    }
}
