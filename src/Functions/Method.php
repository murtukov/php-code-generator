<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Functions;

use Murtukov\PHPCodeGenerator\DocBlockTrait;
use Murtukov\PHPCodeGenerator\Modifier;
use Murtukov\PHPCodeGenerator\Traits\ScopedContentTrait;

class Method extends AbstractFunction
{
    use ScopedContentTrait, DocBlockTrait;

    public function __construct(string $name, string $modifier = Modifier::PUBLIC, string $returnType = '')
    {
        $this->signature = new Signature($name, $modifier, $returnType);
        $this->dependencyAwareChildren = [$this->signature];
    }

    public static function new(string $name, string $modifier = Modifier::PUBLIC, string $returnType = ''): self
    {
        return new static($name, $modifier, $returnType);
    }

    public function generate(): string
    {
        return <<<CODE
        {$this->buildDocBlock(true)}{$this->signature->generate(false)}
        {
        {$this->generateContent()}
        }
        CODE;
    }
}
