<?php

namespace Murtukov\PHPCodeGenerator\Traits;

trait IndentableTrait
{
    private int $indent = 4; // spaces

    protected function indent(string $code): string
    {
        $indent = $this->createOffset();

        return $indent . str_replace("\n", "\n$indent", $code);
    }

    protected function createOffset(): string
    {
        $indent = '';

        for ($i = 0; $i < $this->indent; ++$i) {
            $indent .= " ";
        }

        return $indent;
    }
}