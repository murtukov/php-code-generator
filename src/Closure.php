<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class Closure extends AbstractFunction
{
    use ScopedContentTrait;

    public function __construct(string $returnType = '')
    {
        $this->signature = new Signature('', Modifier::NONE, $returnType);
        $this->dependencyAwareChildren = [$this->signature];
    }

    public static function new(string $returnType = '')
    {
        return new self($returnType);
    }

    public function generate(): string
    {
        return <<<CODE
        $this->signature {
        {$this->generateContent()}
        }
        CODE;
    }
}
