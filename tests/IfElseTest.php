<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\IfElse;
use Murtukov\PHPCodeGenerator\Text;
use PHPUnit\Framework\TestCase;

class IfElseTest extends TestCase
{
    /** @test */
    public function withoutElseEmptyContent()
    {
        $ifElse = IfElse::new('"name" === 15');

        $expected = <<<CODE
        if ("name" === 15) {
          
        }
        CODE;

        $this->assertEquals($expected, (string) $ifElse);
    }

    /** @test */
    public function withElseEmptyContent()
    {
        $ifElse = IfElse::new('true');
        $ifElse->createElse();

        $expected = <<<CODE
        if (true) {
          
        } else {
        
        }
        CODE;

        $this->assertEquals($expected, (string) $ifElse);
    }

    /** @test */
    public function withElseElseIfEmptyContent()
    {
        $ifElse = IfElse::new('true');
        $ifElse->createElse();
        $ifElse->createElseIf('false');

        $expected = <<<CODE
        if (true) {
          
        } elseif (false) {
        
        } else {
        
        }
        CODE;

        $this->assertEquals($expected, (string) $ifElse);
    }

    /** @test */
    public function allPartsWithContent()
    {
        $ifElse = IfElse::new();
        $ifElse->setExpression('$name === 15');
        $ifElse->append('$names = ', "['name' => 'Timur']")
            ->createElseIf(Text::new('$name === 95'))
                ->append('return null')
            ->end()
            ->createElseIf('$name === 95')
                ->append('return ', Text::new('false', true))
            ->end()
            ->createElse()
                ->append('$x = 95')
                ->append('return false')
            ->end();

        $expected = <<<CODE
        if (\$name === 15) {
            \$names = ['name' => 'Timur'];  
        } elseif ('\$name === 95') {
            return null;
        } elseif (\$name === 95) {
            return "false";
        } else {
            \$x = 95;
            return false;
        }
        CODE;

        $this->assertEquals($expected, (string) $ifElse);
    }
}
