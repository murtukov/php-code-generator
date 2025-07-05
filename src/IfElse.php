<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class IfElse extends AbstractGenerator implements BlockInterface
{
    use ScopedContentTrait;

    /** @var GeneratorInterface|string */
    private $expression;

    /** @var ElseIfBlock[] */
    private array $elseIfBlocks = [];

    private ?ElseBlock $elseBlock = null;

    final public function __construct(GeneratorInterface|string $ifExpression = '')
    {
        $this->expression = $ifExpression;
        $this->dependencyAwareChildren = [&$this->content];
    }

    /**
     * @return static
     */
    public static function new(GeneratorInterface|string $ifExpression = ''): self
    {
        return new static($ifExpression);
    }

    public function setExpression(GeneratorInterface|string $expression): self
    {
        $this->expression = $expression;

        return $this;
    }

    // TODO: use stringifier for expressions
    public function generate(): string
    {
        $elseIfBlocks = implode($this->elseIfBlocks);

        return <<<CODE
        if ($this->expression) {
        {$this->generateContent()}
        }{$elseIfBlocks}$this->elseBlock
        CODE;
    }

    public function createElseIf(GeneratorInterface|string $expression = ''): ElseIfBlock
    {
        return $this->elseIfBlocks[] = new ElseIfBlock($expression, $this);
    }

    public function createElse(): ElseBlock
    {
        return $this->elseBlock = new ElseBlock($this);
    }
}
