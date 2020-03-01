<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Functions;

use Murtukov\PHPCodeGenerator\GeneratorInterface;
use Murtukov\PHPCodeGenerator\Traits\FunctionTrait;

class ArrowFunction implements GeneratorInterface
{
    use FunctionTrait;

    /**
     * @var string|GeneratorInterface
     */
    private $expression;

    public function __construct($expression = '', string $returnType = '')
    {
        $this->expression = $expression;
        $this->returnType = $returnType;
    }

    public static function create($expression = '', string $returnType = '')
    {
        return new self($expression, $returnType);
    }

    public function generate(): string
    {
        return "fn({$this->generateArgs()}) => $this->expression";
    }

    public function getExpression(): string
    {
        return $this->expression;
    }

    /**
     * @param string|GeneratorInterface $expression
     * @return ArrowFunction
     */
    public function setExpression($expression): ArrowFunction
    {
        $this->expression = $expression;
        return $this;
    }

    public function __toString(): string
    {
        return $this->generate();
    }
}