<?php

namespace Murtukov\PHPCodeGenerator\Closure;

use Murtukov\PHPCodeGenerator\Argument;
use Murtukov\PHPCodeGenerator\GeneratorInterface;

abstract class AbstractClosure implements GeneratorInterface
{
    protected string  $returnType;
    protected array   $args;

    abstract public function generate(): string;
    abstract protected function generateArgs(): string;

    public function __construct(string $returnType, array $args)
    {
        $this->returnType = $returnType;
        $this->args = $args;
    }

    public function __toString(): string
    {
        return $this->generate();
    }

    public function getReturnType(): string
    {
        return $this->returnType;
    }

    public function setReturnType(string $returnType): AbstractClosure
    {
        $this->returnType = $returnType;
        return $this;
    }

    public function getArguments(): array
    {
        return $this->args;
    }

    public function setArguments(array $args): AbstractClosure
    {
        $this->args = $args;
        return $this;
    }

    public function addArgument(Argument $arg): self
    {
        $this->args[] = $arg;
        return $this;
    }

    public function removeArgument(int $index): self
    {
        unset($this->args[$index]);
        return $this;
    }
}