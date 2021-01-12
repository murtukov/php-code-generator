<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class ElseBlock extends AbstractGenerator
{
    use ScopedContentTrait;

    public IfElse $parent;

    public function __construct(IfElse $parent)
    {
        $this->parent = $parent;
    }

    public function end(): IfElse
    {
        return $this->parent;
    }

    public function generate(): string
    {
        return " else {\n{$this->generateContent()}\n}";
    }
}