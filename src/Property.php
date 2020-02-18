<?php

namespace Murtukov\PHPCodeGenerator;

class Property implements GeneratorInterface
{
    private $name;
    private $modifier;
    private $defaulValue;
    private $isStatic;

    public static function create(string $name, ?string $modifier = null, ?string $defaulValue = null, bool $isStatic = false): self
    {
        return new self($name, $modifier, $defaulValue, $isStatic);
    }

    public function __construct(string $name, string $modifier, ?string $defaulValue = null, bool $isStatic = false)
    {
        $this->name = $name;
        $this->modifier = $modifier ?? 'public';
        $this->defaulValue = $defaulValue;
        $this->isStatic = $isStatic;
    }

    public function generate(): string
    {
        return "$this->modifier $$this->name{$this->getDefaultValue()};";
    }

    private function getDefaultValue(): string
    {
        return $this->defaulValue ? " = $this->defaulValue" : '';
    }

    public function __toString()
    {
        return $this->generate();
    }
}