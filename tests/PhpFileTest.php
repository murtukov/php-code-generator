<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\ControlStructures\Loop;
use Murtukov\PHPCodeGenerator\Functions\Method;
use Murtukov\PHPCodeGenerator\PhpFile;
use PHPUnit\Framework\TestCase;

class PhpFileTest extends TestCase
{
    /**
     * @test
     */
    public function fullBuild()
    {
        $file = PhpFile::new()
            ->setNamespace('App\Converter')
            ->addUseGroup('Symfony\Validator\Converters', '\Symfony\Validator\Converters\NotNull', '\Symfony\Validator\Converters\Length')
            ->addComment('This file was generated and should not be modified manually.');

        $class = $file->createClass('ArrayConverter')
            ->setAbstract()
            ->addDocBlock('Converts arrays into strings');

        $method = $class->createMethod('convert', Method::PUBLIC, 'string');
        $method->addArgument('array', 'array', []);

        $foreach = Loop::foreach('$array as $value')
            ->append('$result[] = "prefix_" . $value');

        $method
            ->emptyLine()
            ->append('$result = []')
            ->emptyLine()
            ->append($foreach)
            ->prepend('$test = false');

        $this->assertEquals(
            <<<CODE
            <?php
            
            namespace App\Converter;
            
            use Symfony\Validator\Converters{NotNull, Length};
            
            /**
             * Converts arrays into strings
             */
            abstract class ArrayConverter 
            {
                public function convert(array \$array = []): string
                {
                    \$test = false;
                    
                    \$result = [];
                    
                    foreach (\$array as \$value) {
                        \$result[] = "prefix_" . \$value;
                    }
                }
            }
            CODE,
            (string) $file
        );
    }
}
