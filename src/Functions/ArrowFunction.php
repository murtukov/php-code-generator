<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Functions;

class ArrowFunction extends AbstractFunction
{
    private string $expression;

    public function __construct(array $args = [], string $returnType = '', string $expression = '')
    {
        $this->expression = $expression;
        $this->setArguments($args);
        parent::__construct($returnType);
    }

    public static function create(array $args = [], string $returnType = '', string $expression = '')
    {
        return new self($args, $returnType, $expression);
    }

    public function generate(): string
    {
        return "fn({$this->generateArgs()}) => $this->expression";
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