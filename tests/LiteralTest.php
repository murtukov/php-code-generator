<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Collection;
use Murtukov\PHPCodeGenerator\Instance;
use Murtukov\PHPCodeGenerator\Literal;
use PHPUnit\Framework\TestCase;

class LiteralTest extends TestCase
{
    /**
     * @test
     */
    public function withoutValues(): void
    {
        $expected = '$foo = "bar";';
        $literal = new Literal($expected);
        $this->assertSame($expected, $literal->generate());
    }

    /**
     * @test
     */
    public function withValues(): void
    {
        $literal = new Literal(
            '$foo = %s; %s',
            new Literal('"bar"'),
            new Literal('echo $foo;')
        );
        $this->assertSame('$foo = "bar"; echo $foo;', $literal->generate());
    }

    /**
     * @test
     */
    public function withValuesAndProtectedPlaceholders(): void
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

    /**
     * @test
     */
    public function withValuesAndProtectedPlaceholdersWithoutValues(): void
    {
        $literal = new Literal(
            'sprintf("This value should not be quoted %%s.", \'foo\');'
        );
        $this->assertSame(
            'sprintf("This value should not be quoted %s.", \'foo\');',
            $literal->generate()
        );
    }

    /**
     * @test
     */
    public function dependenciesAreSet(): void
    {
        $literal = new Literal(
            '$foo = %s; $bar = "%s"',
            Instance::new('App\Entity\User'),
            Collection::numeric()
                ->push(Instance::new('App\Entity\Post'))
                ->push(Instance::new('App\Entity\Profile'))
        );

        $usePaths = $literal->getUsePaths();

        $this->assertCount(3, $usePaths);
        $this->assertArrayHasKey('App\Entity\User', $usePaths);
        $this->assertArrayHasKey('App\Entity\Post', $usePaths);
        $this->assertArrayHasKey('App\Entity\Profile', $usePaths);
    }
}
