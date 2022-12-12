<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Argument;
use Murtukov\PHPCodeGenerator\Collection;
use Murtukov\PHPCodeGenerator\MethodAbstract;
use Murtukov\PHPCodeGenerator\Modifier;
use PHPStan\Testing\TestCase;

class MethodAbstractTest extends TestCase
{

    /**
     * @test
     */
    public function emptyBase(): MethodAbstract
    {
        $method = MethodAbstract::new('myMethod', Modifier::PROTECTED, 'void');

        $this->expectOutputString('protected abstract function myMethod(): void;');

        echo $method;

        return $method;
    }

    /**
     * @test
     * @depends emptyBase
     */
    public function addArguments(MethodAbstract $method): MethodAbstract
    {
        $arg1 = $method->createArgument('arg1', SplHeap::class, null)->setNullable();

        $arg2 = $method->createArgument('arg2', 'string', '');
        $arg2->setByReference();

        $method->add(Argument::new('arg3'));
        $method->addArguments('arg4', 'arg5');

        $this->assertEquals($arg1, $method->getArgument(1));
        $this->assertEquals($arg2, $method->getArgument(2));

        $this->expectOutputString(
            'protected abstract function myMethod(?SplHeap $arg1 = null, string &$arg2 = \'\', $arg3, $arg4, $arg5): void;'
        );

        echo $method;

        return $method;
    }

    /**
     * @test
     * @depends addArguments
     */
    public function modifyParts(MethodAbstract $method): MethodAbstract
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
        protected abstract static function myMethod(?SplHeap $arg1 = null, string &$arg2 = '', $arg3, $arg4, ...$arg5): Collection;
        CODE);

        echo $method;

        return $method;
    }


    /**
     * @test
     * @depends modifyParts
     */
    public function removeParts(MethodAbstract $method): void
    {
        $method->removeArgument(1);
        $method->removeArgument(2);
        $method->removeArgument(3);
        $method->unsetStatic();
        $method->clearContent();
        $method->removeDocBlock();

        $this->expectOutputString(<<<'CODE'
        protected abstract function myMethod($arg4, ...$arg5): Collection;
        CODE);

        echo $method;
    }

    /**
     * @test
     */
    public function withPromotedArguments(): MethodAbstract
    {
        $method = MethodAbstract::new('__construct');

        $method->addArgument('firstName', 'string', 'Alex', Modifier::PRIVATE);
        $method->addArgument('lastName', 'string', 'Kowalski', Modifier::PRIVATE);
        $method->addArgument('age', 'int', Argument::NO_PARAM, Modifier::PRIVATE);

        $method->signature->setMultiline();

        echo $method;

        $this->expectOutputString(<<<'CODE'
        public abstract function __construct(
            private string $firstName = 'Alex',
            private string $lastName = 'Kowalski',
            private int $age
        );
        CODE);

        return $method;
    }


    /**
     * @test
     * @depends withPromotedArguments
     */
    public function addNormalArguments(MethodAbstract $method): MethodAbstract
    {
        $method->addArgument('isStudent', 'bool');
        $method->addArgument('isEmployed');

        echo $method;

        $this->expectOutputString(<<<'CODE'
        public abstract function __construct(
            private string $firstName = 'Alex',
            private string $lastName = 'Kowalski',
            private int $age,
            bool $isStudent,
            $isEmployed
        );
        CODE);

        return $method;
    }

}
