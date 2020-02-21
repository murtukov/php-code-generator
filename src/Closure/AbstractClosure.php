<?php

namespace Murtukov\PHPCodeGenerator\Closure;

use Murtukov\PHPCodeGenerator\AbstractGenerator;
use Murtukov\PHPCodeGenerator\Argument;

abstract class AbstractClosure extends AbstractGenerator
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

    public function getReturnType(): string
    {
        return $this->returnType;
    }

    public function setReturnType(string $returnType): self
    {
        $this->returnType = $returnType;
        return $this;
    }

    public function getArguments(): array
    {
        return $this->args;
    }

    public function setArguments(array $args): self
    {
        $this->args = $args;
        return $this;
    }

    public function addArgument(Argument $arg): self
    {
        $this->args[] = $arg;
        return $this;
    }

    public function createArgument()
    {
        return new class {

        };
    }

    public function removeArgumentAt(int $index): self
    {
        unset($this->args[$index]);
        return $this;
    }
}