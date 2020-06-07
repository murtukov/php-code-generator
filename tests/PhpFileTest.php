<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Block;
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
            ->addComment('This file was generated and should not be modified manually.');

        $class = $file->createClass('ArrayConverter')
            ->setAbstract()
            ->addDocBlock('Converts arrays into strings');

        $method = $class->createMethod('convert', Method::PUBLIC, 'string');
        $method->addArgument('array', 'array', []);

        $foreachBody = Block::new();

        $method->append('$result = []');
        $method->append('foreach($array as $value) ', $foreachBody);

        $foreachBody
            ->append('$result[] = "prefix_" . $value');

        $result = $file->generate();
    }
}
