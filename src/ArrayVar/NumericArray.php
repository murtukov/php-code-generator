<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\ArrayVar;

class NumericArray extends AbstractArray
{
    public function generate(): string
    {
        if (!$this->multiline) {
            return '['.implode(', ', $this->items).']';
        }

        $indent = $this->getIndent();

        return "[\n$indent".implode("\n$indent", $this->items)."\n]";
    }
}