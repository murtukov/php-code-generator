<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Argument;
use Murtukov\PHPCodeGenerator\Closure;
use Murtukov\PHPCodeGenerator\Loop;
use PHPUnit\Framework\TestCase;

class ClosureTest extends TestCase
{
    /**
     * @test
     */
    public function emptyBase()
    {
        $closure = Closure::new('array');

        $this->expectOutputString(<<<CODE
        function (): array {
        
        }
        CODE);

        echo $closure;

        return $closure;
    }

    /**
     * @test
     * @depends emptyBase
     */
    public function addArguments(CLosure $closure)
    {
        $closure->addArgument('value');

        $closure->createArgument('options')
            ->setType('array')
            ->setDefaultValue([]);

        $arg = Argument::new('filter', 'bool', false);

        $closure->add($arg);

        $this->expectOutputString(<<<'CODE'
        function ($value, array $options = [], bool $filter = false): array {
        
        }
        CODE);

        echo $closure;

        return $closure;
    }

    /**
     * @test
     * @depends addArguments
     */
    public function bindVars(Closure $closure)
    {
        $closure->bindVars('this', 'name');
        $closure->bindVar('global', true);

        $this->expectOutputString(<<<'CODE'
        function ($value, array $options = [], bool $filter = false) use ($this, $name, &$global): array {
        
        }
        CODE);

        echo $closure;

        return $closure;
    }

    /**
     * @test
     * @depends bindVars
     */
    public function addContent(Closure $closure)
    {
        $foreach = Loop::foreach('$options as &$option')
            ->append('unset($option)');

        $closure->append($foreach);

        $this->expectOutputString(<<<'CODE'
        function ($value, array $options = [], bool $filter = false) use ($this, $name, &$global): array {
            foreach ($options as &$option) {
                unset($option);
            }
        }
        CODE);

        echo $closure;

        return $closure;
    }

    /**
     * @test
     * @depends addContent
     */
    public function modifyParts(Closure $closure)
    {
        $closure->setStatic();
        $closure->removeArguments();
        $closure->removeBindVars();
        $closure->setReturnType('');

        $this->expectOutputString(<<<'CODE'
        static function () {
            foreach ($options as &$option) {
                unset($option);
            }
        }
        CODE);

        echo $closure;
    }
}
