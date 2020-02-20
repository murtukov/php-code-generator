<?php

namespace Murtukov\PHPCodeGenerator\Closure;

class ArrowFunction extends AbstractClosure
{
    private string $expression;

    public function __construct(array $args = [], string $returnType = '', string $expression = '')
    {
        $this->expression = $expression;
        parent::__construct($returnType, $args);
    }

    public static function create(array $args = [], string $returnType = '', string $expression = '')
    {
        return new self($args, $returnType, $expression);
    }

    public function generate(): string
    {
        return "fn({$this->generateArgs()}) => $this->expression";
    }

    protected function generateArgs(): string
    {
        return \implode(", ", $this->args);
    }

    public function getExpression(): string
    {
        return $this->expression;
    }

    public function setExpression(string $expression): ArrowFunction
    {
        $this->expression = $expression;
        return $this;
    }
}