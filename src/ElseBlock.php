<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class ElseBlock extends AbstractGenerator
{
    use ScopedContentTrait;

    public function __construct(public IfElse $parent)
    {
        $this->dependencyAwareChildren = [&$this->content];
    }

    public function end(): IfElse
    {
        return $this->parent;
    }

    public function generate(): string
    {
        return <<<CODE
         else {
        {$this->generateContent()}
        }
        CODE;
    }
}
