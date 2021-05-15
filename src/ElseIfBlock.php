<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class ElseIfBlock extends AbstractGenerator
{
    use ScopedContentTrait;

    /** @var GeneratorInterface|string */
    public $expression;
    public IfElse $parent;

    /**
     * @param GeneratorInterface|string $expression
     */
    public function __construct($expression, IfElse $parent)
    {
        $this->expression = $expression;
        $this->parent = $parent;

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
