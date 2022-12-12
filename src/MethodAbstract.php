<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class MethodAbstract extends AbstractFunction implements BlockInterface
{
    use ScopedContentTrait;
    use DocBlockTrait;

    final public function __construct(string $name, string $modifier = Modifier::PUBLIC, string $returnType = '')
    {
        $this->signature = new Signature($name, "$modifier abstract", $returnType);
        $this->dependencyAwareChildren = [$this->signature];
    }

    /**
     * @return static
     */
    public static function new(string $name, string $modifier = Modifier::PUBLIC, string $returnType = ''): self
    {
        return new static($name, $modifier, $returnType);
    }

    public function generate(): string
    {
        return $this->buildDocBlock().$this->signature->generate(false).';';
    }

}
