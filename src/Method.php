<?php

namespace Murtukov\PHPCodeGenerator;

use function array_unshift;
use function implode;

class Method implements GeneratorInterface
{
    private string  $name;
    private string  $modifier;
    private string  $returnType;
    private array   $args = [];
    private array   $content = [];
    private int     $indent = 4;   // spaces

    public static function create(string $name, string $modifier = 'public', string $returnType = ''): self
    {
        return new self($name, $modifier, $returnType);
    }

    public function __construct(string $name, string $modifier = 'public', string $returnType = '')
    {
        $this->name = $name;
        $this->modifier = $modifier;
        $this->returnType = $returnType;
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

    private function indent(string $code): string
    {
        $indent = $this->createOffset();

        return $indent . str_replace("\n", "\n$indent", $code);
    }

    private function createOffset(): string
    {
        $indent = '';
        
        for ($i = 0; $i < $this->indent; ++$i) {
            $indent .= " ";
        }

        return $indent;
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

    public function getIndent(): int
    {
        return $this->indent;
    }

    public function setIndent(int $indent): Method
    {
        $this->indent = $indent;
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


}


function method(string $name, string $modifier = 'public', ?string $returnType = null): Method
{
    return new Method($name, $modifier, $returnType);
}