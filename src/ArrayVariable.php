<?php

namespace Murtukov\PHPCodeGenerator;

class ArrayVariable implements GeneratorInterface
{
    private bool    $oldSyntax = false;
    private bool    $oneLiner = false;
    private bool    $numeric = true;
    private array   $items = [];

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