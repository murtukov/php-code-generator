<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class ArrowFunction extends AbstractFunction
{
    /** @var GeneratorInterface|string */
    private $expression;

    public function __construct($expression = '', string $returnType = '')
    {
        $this->signature = new Signature('', Modifier::NONE, $returnType, 'fn');
        $this->expression = $this->manageExprDependency($expression);

        $this->dependencyAwareChildren[] = $this->signature;
    }

    public static function new($expression = '', string $returnType = '')
    {
        return new self($expression, $returnType);
    }

    public function generate(): string
    {
        return "$this->signature => $this->expression";
    }

    /**
     * @return GeneratorInterface|string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @param GeneratorInterface|string $expression
     */
    public function setExpression($expression): self
    {
        $this->expression = $this->manageExprDependency($expression);

        return $this;
    }

    protected function manageExprDependency($value)
    {
        if ($value instanceof DependencyAwareGenerator) {
            $this->dependencyAwareChildren['expr'] = $value;
        } elseif (is_string($value)) {
            unset($this->dependencyAwareChildren['expr']);
        }

        return $value;
    }
}
