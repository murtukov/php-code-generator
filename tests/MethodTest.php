<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Argument;
use Murtukov\PHPCodeGenerator\Collection;
use Murtukov\PHPCodeGenerator\Instance;
use Murtukov\PHPCodeGenerator\Method;
use Murtukov\PHPCodeGenerator\Modifier;
use PHPUnit\Framework\TestCase;

class MethodTest extends TestCase
{
    /**
     * @test
     */
    public function emptyBase(): Method
    {
        $method = Method::new('myMethod', Modifier::PRIVATE, 'void');

        $this->expectOutputString(<<<CODE
        private function myMethod(): void
        {
        
        }
        CODE);

        echo $method;

        return $method;
    }

    /**
     * @test
     * @depends emptyBase
     */
    public function addContent(Method $method): Method
    {
        $method->append('$object = ', Instance::new(stdClass::class));

        $this->expectOutputString(<<<'CODE'
        private function myMethod(): void
        {
            $object = new stdClass();
        }
        CODE);

        echo $method;

        return $method;
    }

    /**
     * @test
     * @depends addContent
     */
    public function addArguments(Method $method): Method
    {
        $arg1 = $method->createArgument('arg1', SplHeap::class, null)->setNullable();

        $arg2 = $method->createArgument('arg2', 'string', '');
        $arg2->setByReference();

        $method->add(Argument::new('arg3'));
        $method->addArguments('arg4', 'arg5');

        $this->assertEquals($arg1, $method->getArgument(1));
        $this->assertEquals($arg2, $method->getArgument(2));

        $this->expectOutputString(<<<'CODE'
        private function myMethod(?SplHeap $arg1 = null, string &$arg2 = '', $arg3, $arg4, $arg5): void
        {
            $object = new stdClass();
        }
        CODE);

        echo $method;

        return $method;
    }

    /**
     * @test
     * @depends addArguments
     */
    public function modifyParts(Method $method): Method
    {
        $method->setStatic();
        $method->setReturnType(Collection::class);
        $method->setDocBlock(<<<'DOCBLOCK'
        Another simple function
        
        @param SqlHeap|null $arg1
        @param string       $arg2
        @param mixed        $arg3
        DOCBLOCK);

        /** @var Argument $arg */
        $arg = $method->getArgument(5);
        $arg->setSpread();

        $this->assertEquals(true, $method->isStatic());
        $this->assertEquals('Collection', $method->getReturnType());

        $this->expectOutputString(<<<'CODE'
        /**
         * Another simple function
         * 
         * @param SqlHeap|null $arg1
         * @param string       $arg2
         * @param mixed        $arg3
         */
        private static function myMethod(?SplHeap $arg1 = null, string &$arg2 = '', $arg3, $arg4, ...$arg5): Collection
        {
            $object = new stdClass();
        }
        CODE);

        echo $method;

        return $method;
    }

    /**
     * @test
     * @depends modifyParts
     */
    public function removeParts(Method $method): void
    {
        $method->removeArgument(1);
        $method->removeArgument(2);
        $method->removeArgument(3);
        $method->unsetStatic();
        $method->clearContent();
        $method->removeDocBlock();

        $this->expectOutputString(<<<'CODE'
        private function myMethod($arg4, ...$arg5): Collection
        {
        
        }
        CODE);

        echo $method;
    }
}
