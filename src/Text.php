<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class Text extends AbstractGenerator
{
    public function __construct(
        public string $value,
        public bool $doubleQuotes = false,
    ) {
    }

    public static function new(string $value, bool $doubleQuotes = false): self
    {
        return new self($value, $doubleQuotes);
    }

    public function generate(): string
    {
        if ($this->doubleQuotes) {
            return '"'.$this->value.'"';
        } else {
            return "'$this->value'";
        }
    }
}
