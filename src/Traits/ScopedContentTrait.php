<?php


namespace Murtukov\PHPCodeGenerator\Traits;


use Murtukov\PHPCodeGenerator\Functions\ArrowFunction;
use Murtukov\PHPCodeGenerator\GeneratorInterface;

trait ScopedContentTrait
{
    private array $content = [];

    public function appendVar(string $name, GeneratorInterface $var): self
    {
        $this->content[] = "$$name = $var";

        return $this;
    }

    public function append(GeneratorInterface $object): self
    {
        $this->content[] = $object;

        return $this;
    }

    public function prepend(GeneratorInterface $object): self
    {
        array_unshift($this->content, $object);

        return $this;
    }

    public function appendEmptyLine(): self
    {
        $this->content[] = "\n";

        return $this;
    }

    public function appendFn(array $args = [], string $returnType = '', string $expression = ''): ArrowFunction
    {
        return $this->content[] = new ArrowFunction($args, $returnType, $expression);
    }
}