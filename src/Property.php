<?php

namespace Murtukov\PHPCodeGenerator;

class Property implements GeneratorInterface
{
    private string  $name;
    private string  $modifier;
    private string  $defaulValue;


    public function __construct(string $name, string $modifier, string $defaulValue)
    {
        $this->name = $name;
        $this->modifier = $modifier;
        $this->defaulValue = $defaulValue;
    }


    public function generate(): string
    {
        return '';
    }
}