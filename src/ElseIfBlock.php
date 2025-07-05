<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class ElseIfBlock extends AbstractGenerator
{
    use ScopedContentTrait;

    public function __construct(
        public GeneratorInterface|string $expression,
        public IfElse $parent,
    ) {
        $this->dependencyAwareChildren = [&$this->content];
    }

    public function generate(): string
    {
        return <<<CODE
         elseif ($this->expression) {
        {$this->generateContent()}
        }
        CODE;
    }

    public function end(): IfElse
    {
        return $this->parent;
    }
}
