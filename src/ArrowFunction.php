<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class ArrowFunction extends AbstractFunction
{
    /** @var GeneratorInterface|string */
    private $expression;

    /**
     * @param mixed $expression
     */
    final public function __construct($expression = null, string $returnType = '')
    {
        $this->signature = new Signature('', Modifier::NONE, $returnType, 'fn');
        $this->expression = $this->manageExprDependency($expression);

        $this->dependencyAwareChildren[] = $this->signature;
    }

    /**
     * @param mixed $expression
     *
     * @return static
     */
    public static function new($expression = null, string $returnType = ''): ArrowFunction
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
     * @param mixed $expression
     *
     * @return $this
     */
    public function setExpression($expression): self
    {
        $this->expression = $this->manageExprDependency($expression);

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    protected function manageExprDependency($value)
    {
        if ($value instanceof DependencyAwareGenerator) {
            $this->dependencyAwareChildren['expr'] = $value;
        } else {
            unset($this->dependencyAwareChildren['expr']);
        }

        return $value;
    }
}
