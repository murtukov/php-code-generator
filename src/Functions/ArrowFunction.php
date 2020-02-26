<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Functions;

use Murtukov\PHPCodeGenerator\Traits\FunctionTrait;

class ArrowFunction extends AbstractFunction
{
    use FunctionTrait;

    private string $expression;

    public function __construct(string $returnType = '', string $expression = '')
    {
        $this->expression = $expression;
        $this->returnType = $returnType;
    }

    public static function create(string $returnType = '', string $expression = '')
    {
        return new self($returnType, $expression);
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