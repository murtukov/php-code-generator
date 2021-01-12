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

    /**
     * @param GeneratorInterface|string $ifExpression
     */
    public final function __construct($ifExpression = '')
    {
        $this->expression = $ifExpression;
    }

    /**
     * @param string $ifExpression
     * @return static
     */
    public static function new($ifExpression = ''): self
    {
        return new static($ifExpression);
    }

    /**
     * @param GeneratorInterface|string $expression
     */
    public function setExpression($expression): self
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

    /**
     * @param GeneratorInterface|string $expression
     */
    public function createElseIf($expression = ''): ElseIfBlock
    {
        return $this->elseIfBlocks[] = new ElseIfBlock($expression, $this);
    }

    public function createElse(): ElseBlock
    {
        return $this->elseBlock = new ElseBlock($this);
    }
}
