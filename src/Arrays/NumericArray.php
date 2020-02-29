<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Arrays;

use Murtukov\PHPCodeGenerator\GeneratorInterface;

class NumericArray extends AbstractArray
{
    public function generate(): string
    {
        if (empty($this->items)) {
            return '[]';
        }

        if ($this->multiline) {
            return "[\n{$this->indent(implode(",\n", $this->items))}\n]";
        }

        return '['.implode(', ', $this->items).']';
    }

    /**
     * @param string|GeneratorInterface $item
     * @return $this
     */
    public function push($item): self
    {
        $this->items[] = $item;

        return $this;
    }
}