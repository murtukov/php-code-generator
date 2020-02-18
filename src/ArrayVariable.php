<?php

namespace Murtukov\PHPCodeGenerator;

class ArrayVariable implements GeneratorInterface
{
    private $oldSyntax = false;
    private $oneLiner = false;
    private $numeric = true;
    private $items = [];

    public function addItem(string $key, string $value): self
    {
        $this->items[$key] = $value;

        return $this;
    }


    public function generate(): string
    {
        return '';
    }
}