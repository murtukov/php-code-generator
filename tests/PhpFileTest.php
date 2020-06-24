<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Comment;
use Murtukov\PHPCodeGenerator\ControlStructures\Loop;
use Murtukov\PHPCodeGenerator\Method;
use Murtukov\PHPCodeGenerator\Instance;
use Murtukov\PHPCodeGenerator\Modifier;
use Murtukov\PHPCodeGenerator\PhpFile;
use Murtukov\PHPCodeGenerator\Qualifier;
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
            ->addUseGroup('Symfony\Validator\Converters', 'NotNull', 'Symfony\Validator\Converters\Length')
            ->addComment('This file was generated and should not be modified manually.');

        $class = $file->createClass('ArrayConverter')
            ->setAbstract()
            ->addDocBlock('Converts arrays into strings');

        $class->createConstructor();

        $method = $class->createMethod('convert', Modifier::PUBLIC, 'string');
        $method->addArgument('array', 'array', []);

        $foreach = Loop::foreach('$array as $value')
            ->append('$result[] = "prefix_" . $value')
            ->emptyLine()
            ->append(Comment::slash('Some comment'))
            ->append(Qualifier::new(Method::class), "::new('__toString')");

        $method
            ->emptyLine()
            ->append('$result = []')
            ->emptyLine()
            ->append($foreach)
            ->prepend('$test = false');

        $class
            ->createMethod('getPhpFile', Modifier::PRIVATE, PhpFile::class)
            ->setStatic()
            ->append('return ', Instance::new(PhpFile::class))
        ;

        $this->expectOutputString(<<<CODE
        <?php
        
        namespace App\Converter;
        
        use Murtukov\PHPCodeGenerator\Method;
        use Murtukov\PHPCodeGenerator\PhpFile;
        use Symfony\Validator\Converters\{NotNull, Length};
        
        /**
         * Converts arrays into strings
         */
        abstract class ArrayConverter
        {
            public function __construct()
            {
            
            }
            
            public function convert(array \$array = []): string
            {
                \$test = false;
                
                \$result = [];
                
                foreach (\$array as \$value) {
                    \$result[] = "prefix_" . \$value;
                    
                    // Some comment
                    Method::new('__toString');
                }
            }
            
            private static function getPhpFile(): PhpFile
            {
                return new PhpFile();
            }
        }
        CODE);

        echo $file;
    }
}
