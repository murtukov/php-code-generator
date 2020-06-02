<?php declare(strict_types=1);

namespace ControlStructures;

use Murtukov\PHPCodeGenerator\ControlStructures\IfElse;
use Murtukov\PHPCodeGenerator\Text;
use PHPUnit\Framework\TestCase;

class IfElseTest extends TestCase
{
    /** @test */
    public function withoutElseEmptyContent()
    {
        $ifElse = IfElse::create('"name" === 15');

        $expected = <<<CODE
        if ("name" === 15) {
          
        }
        CODE;

        $this->assertEquals($expected, (string) $ifElse);
    }

    /** @test */
    public function withElseEmptyContent()
    {
        $ifElse = IfElse::create('true');
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
        $ifElse = IfElse::create('true');
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
         $ifElse = IfElse::create('$name === 15');
         $ifElse->append('$names = ', "['name' => 'Timur']")
            ->createElseIf(new Text('$name === 95'))
                ->append('return null')
            ->end()
            ->createElseIf('$name === 95')
                ->append('return null')
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
            return null;
        } else {
            \$x = 95;
            return false;
        }
        CODE;

        $this->assertEquals($expected, (string) $ifElse);
    }
}
