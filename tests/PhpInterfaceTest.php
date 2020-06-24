<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\BlockInterface;
use Murtukov\PHPCodeGenerator\ConverterInterface;
use Murtukov\PHPCodeGenerator\Method;
use Murtukov\PHPCodeGenerator\Modifier;
use Murtukov\PHPCodeGenerator\PhpInterface;
use PHPUnit\Framework\TestCase;

class PhpInterfaceTest extends TestCase
{
    /**
     * @test
     */
    public function emptyBase()
    {
        $code = <<<CODE
        interface StringifierInterface
        {
        
        }
        CODE;

        $interface = PhpInterface::new('StringifierInterface');
        $this->assertEquals($code, $interface->generate());

        return $interface;
    }

    /**
     * @test
     * @depends emptyBase
     */
    public function addExtends(PhpInterface $interface)
    {
        $code = <<<CODE
        interface StringifierInterface extends BlockInterface, ConverterInterface
        {
        
        }
        CODE;

        $interface->addExtends(BlockInterface::class, ConverterInterface::class);
        $this->assertEquals($code, $interface->generate());

        return $interface;
    }

    /**
     * @test
     * @depends addExtends
     */
    public function addConsts(PhpInterface $interface)
    {
        $code = <<<CODE
        interface StringifierInterface extends BlockInterface, ConverterInterface
        {
            public const NAME = 'MyInterface';
            public const TYPE = 'Component';
        }
        CODE;

        $interface->addConst('NAME', 'MyInterface');
        $interface->addConst('TYPE', 'Component');

        $this->assertEquals($code, $interface->generate());

        return $interface;
    }

    /**
     * @test
     * @depends addConsts
     */
    public function addSignatures(PhpInterface $interface)
    {
        $code = <<<CODE
        interface StringifierInterface extends BlockInterface, ConverterInterface
        {
            public const NAME = 'MyInterface';
            public const TYPE = 'Component';
            
            public function parse(): string;
            
            /**
             * Convert value to string.
             */
            public function stringify(bool \$escapeSlashes = false): string;
            
            public function dump(): string;
        }
        CODE;

        $interface->emptyLine();
        $interface->addSignature('parse', 'string');

        $interface->emptyLine();

        $stringifyMethod = $interface->createSignature('stringify', 'string');
        $stringifyMethod->addArgument('escapeSlashes', 'bool', false);
        $stringifyMethod->addDocBlock('Convert value to string.');

        $interface->emptyLine();

        $dumpMethod = Method::new('dump', Modifier::PUBLIC, 'string');
        $interface->addSignatureFromMethod($dumpMethod);

        $this->assertEquals($code, $interface->generate());
    }
}
