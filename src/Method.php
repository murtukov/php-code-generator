<?php

namespace Murtukov\PHPCodeGenerator;

use function array_unshift;
use function implode;

class Method implements GeneratorInterface
{
    private $name;
    private $modifier;
    private $returnType;
    private $args = [];
    private $content = []; // todo
    private $return;
    private $indent = 4;


    public static function create(string $name, string $modifier = 'public', ?string $returnType = null): self
    {
        return new self($name, $modifier, $returnType);
    }


    public function __construct(string $name, string $modifier = 'public', ?string $returnType = null)
    {
        $this->name = $name;
        $this->modifier = $modifier;
        $this->returnType = $returnType;
    }


    public function generate(): string
    {
        return <<<CODE
        $this->modifier function $this->name(){$this->getReturnType()}
        {
        {$this->getContent()}
        }
        CODE;
    }


    private function getContent(): string
    {
        $content = '';

        if (count($this->content) > 0) {
            $content = $this->indent(implode(";\n", $this->content).';');
        }

        return $content;
    }

    /**
     * Adds offsets to each line in the code.
     *
     * @param string $code
     * @return string
     */
    private function indent(string $code): string
    {
        $indent = $this->getIndent();

        return $indent . str_replace("\n", "\n$indent", $code);
    }

    private function getIndent(): string
    {
        $indent = '';

        for ($i = 0; $i < $this->indent; ++$i) {
            $indent .= " ";
        }

        return $indent;
    }

    private function getReturnType(): string
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
}