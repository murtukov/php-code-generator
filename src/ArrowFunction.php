<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class ArrowFunction extends AbstractFunction
{
    /** @var GeneratorInterface|string */
    private $expression;

    final public function __construct(mixed $expression = null, string $returnType = '')
    {
        $this->signature = new Signature('', Modifier::NONE, $returnType, 'fn');
        $this->expression = $this->manageExprDependency($expression);

        $this->dependencyAwareChildren[] = $this->signature;
    }

    /**
     * @return static
     */
    public static function new(mixed $expression = null, string $returnType = ''): ArrowFunction
    {
        return new static($expression, $returnType);
    }

    public function generate(): string
    {
        $expression = Utils::stringify($this->expression);

        return "$this->signature => $expression";
    }

    /**
     * @return GeneratorInterface|string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @return $this
     */
    public function setExpression(mixed $expression): self
    {
        $this->expression = $this->manageExprDependency($expression);

        return $this;
    }

    protected function manageExprDependency(mixed $value)
    {
        if ($value instanceof DependencyAwareGenerator) {
            $this->dependencyAwareChildren['expr'] = $value;
        } else {
            unset($this->dependencyAwareChildren['expr']);
        }

        return $value;
    }
}
