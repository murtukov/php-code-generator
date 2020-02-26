<?php

namespace Murtukov\PHPCodeGenerator;

class GeneratorMap extends AbstractGenerator
{
    private array $map;
    private array $values;


    public function __construct(array $values, array $map)
    {
        $this->values = $values;
        $this->map = $map;
    }

    public function generate(): string
    {


        return '';
    }
}