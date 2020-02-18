<?php

namespace Murtukov\PHPCodeGenerator;

class Method implements GeneratorInterface
{
    private string  $name;
    private string  $modifier;
    private ?string $returnType;
    private array   $args = [];
    private array   $lines = []; // todo
    private string  $return;


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
        return '';
    }

    private function getReturnType(): string
    {
        return ": $this->returnType" ?? '';
    }
}