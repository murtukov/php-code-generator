<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Comment;
use Murtukov\PHPCodeGenerator\Loop;
use PHPUnit\Framework\TestCase;

class LoopTest extends TestCase
{
    /**
     * @test
     */
    public function allLoops(): void
    {
        $for = Loop::for('$i = 1; $i < 1000; ++$i')
            ->append('$x = $i')
            ->emptyLine()
            ->append(Comment::hash('Ok, stop now...'))
            ->append('break');

        $this->assertEquals(<<<'CODE'
        for ($i = 1; $i < 1000; ++$i) {
            $x = $i;
            
            # Ok, stop now...
            break;
        }
        CODE, $for->generate());

        $foreach = Loop::foreach('$apples as $apple')
            ->append('$x = $apple')
            ->append('continue');

        $this->assertEquals(<<<'CODE'
        foreach ($apples as $apple) {
            $x = $apple;
            continue;
        }
        CODE, $foreach->generate());

        $while = Loop::while('true')
            ->append('$x = $i')
            ->append('break');

        $this->assertEquals(<<<'CODE'
        while (true) {
            $x = $i;
            break;
        }
        CODE, $while->generate());

        $doWhile = Loop::doWhile('true')
            ->append(Comment::block('Hello, World!'));

        $this->assertEquals(<<<'CODE'
        do {
            /*
             * Hello, World!
             */
        } while (true)
        CODE, $doWhile->generate());
    }
}
