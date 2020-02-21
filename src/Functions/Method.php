<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Functions;

use Murtukov\PHPCodeGenerator\GeneratorInterface;
use Murtukov\PHPCodeGenerator\Traits\IndentableTrait;
use function array_unshift;
use function implode;

class Method extends AbstractFunction
{
    use IndentableTrait;

    private string  $name;
    private string  $modifier;
    private array   $content = [];
    private array   $customStack = [];

    public static function create(string $name, string $modifier = 'public', string $returnType = ''): self
    {
        return new self($name, $modifier, $returnType);
    }

    public function __construct(string $name, string $modifier = 'public', string $returnType = '')
    {
        $this->name = $name;
        $this->modifier = $modifier;
        $this->returnType = $returnType;

        parent::__construct($returnType);
    }

    public function generate(): string
    {
        $signature = "$this->modifier function $this->name()";

        if ($this->returnType) {
            $signature .= ": $this->returnType";
        }

        return <<<CODE
        $signature
        {
        {$this->generateContent()}
        }
        CODE;
    }

    private function generateContent(): string
    {
        $content = '';

        if (count($this->content) > 0) {
            $content = $this->indent(implode(";\n", $this->content).';');
        }

        return $content;
    }

    private function generateReturnType(): string
    {
        return $this->returnType ? ": $this->returnType" : '';
    }

    public function __toString(): string
    {
        return $this->generate();
    }

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

    public function getReturnType(): string
    {
        return $this->returnType;
    }

    public function setReturnType(string $returnType): Method
    {
        $this->returnType = $returnType;
        return $this;
    }

    public function appendFn(array $args = [], string $returnType = '', string $expression = ''): ArrowFunction
    {
        return $this->content[] = new ArrowFunction($args, $returnType, $expression);
    }
}