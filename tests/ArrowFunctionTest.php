<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\ArrowFunction;
use PHPUnit\Framework\TestCase;

class ArrowFunctionTest extends TestCase
{
    /**
     * @test
     */
    public function emptyBody(): ArrowFunction
    {
        $arrow = ArrowFunction::new();

        $this->expectOutputString(<<<CODE
        fn() => null
        CODE);

        echo $arrow;

        return $arrow;
    }

    /**
     * @test
     *
     * @depends emptyBody
     */
    public function setExpression(ArrowFunction $arrow): array
    {
        $innerArrow = ArrowFunction::new([
            'name' => 'Alrik',
            'age' => 30,
        ]);

        $arrow->setExpression($innerArrow);

        $this->assertEquals($innerArrow, $arrow->getExpression());

        $template = <<<CODE
        fn() => fn() => [
            'name' => 'Alrik',
            'age' => 30,
        ]
        CODE;

        $this->expectOutputString($template);

        echo $arrow;

        return [$arrow, $template];
    }

    /**
     * @test
     *
     * @depends setExpression
     */
    public function setStatic(array $values): void
    {
        [$arrow, $template] = $values;

        $arrow->setStatic();
        $this->expectOutputString('static '.$template);

        echo $arrow;
    }
}
