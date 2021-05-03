<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class Closure extends AbstractFunction
{
    use ScopedContentTrait;

    final public function __construct(string $returnType = '')
    {
        $this->signature = new Signature('', Modifier::NONE, $returnType);
        $this->dependencyAwareChildren = [$this->signature];
    }

    /**
     * @return static
     */
    public static function new(string $returnType = ''): self
    {
        return new static($returnType);
    }

    public function generate(): string
    {
        return <<<CODE
        $this->signature {{$this->generateContent()}}
        CODE;
    }
}
