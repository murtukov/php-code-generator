<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class Method extends AbstractFunction implements BlockInterface
{
    use ScopedContentTrait;
    use DocBlockTrait;

    final public function __construct(
        string $name,
        Modifier $modifier = Modifier::PUBLIC,
        string $returnType = '',
    ) {
        $this->signature = new Signature($name, $modifier, $returnType);
        $this->dependencyAwareChildren = [$this->signature, &$this->content];
    }

    /**
     * @return static
     */
    public static function new(string $name, Modifier $modifier = Modifier::PUBLIC, string $returnType = ''): self
    {
        return new static($name, $modifier, $returnType);
    }

    public function generate(): string
    {
        if ($this->signature->isMultiline) {
            return $this->buildDocBlock().$this->signature->generate(false)." {{$this->generateWrappedContent()}}";
        }

        return <<<CODE
        {$this->buildDocBlock()}{$this->signature->generate(false)}
        {{$this->generateWrappedContent("\n", '')}
        }
        CODE;
    }
}
