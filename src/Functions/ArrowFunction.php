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

    public function __construct(string $returnType = '', $expression = '')
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