<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Functions;

use Murtukov\PHPCodeGenerator\DependencyAwareGenerator;
use Murtukov\PHPCodeGenerator\GeneratorInterface;
use Murtukov\PHPCodeGenerator\Traits\FunctionTrait;

class ArrowFunction extends DependencyAwareGenerator
{
    use FunctionTrait;

    private ?GeneratorInterface $expression;

    public function __construct(?GeneratorInterface $expression, string $returnType = '')
    {
        $this->expression = $expression;
        $this->returnType = $returnType;

        $this->dependencyAwareChildren = [&$this->args, &$this->expression];
    }

    public static function create($expression = null, string $returnType = '')
    {
        return new self($expression, $returnType);
    }

    public function generate(): string
    {
        return "fn({$this->generateArgs()}) => $this->expression";
    }

    public function getExpression(): ?GeneratorInterface
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