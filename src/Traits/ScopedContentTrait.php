<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Traits;

use Murtukov\PHPCodeGenerator\Functions\ArrowFunction;
use Murtukov\PHPCodeGenerator\GeneratorInterface;
use function array_unshift;

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
        return $this->content[] = new ArrowFunction($returnType, $expression);
    }

    public function setReturn(GeneratorInterface $object): self
    {
        $this->content[] = "return $object";

        return $this;
    }

    private function generateContent(): string
    {
        $content = '';

        if (!empty($this->content)) {
            $content = $this->indent(implode(";\n", $this->content).';');
        }

        return $content;
    }
}