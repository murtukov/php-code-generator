<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class CustomCode implements GeneratorInterface
{
    private string $value;

    public function __construct(string $string)
    {
        $this->value = $string;
    }

    public static function create(string $string)
    {
        return new static($string);
    }

    public function generate(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->generate();
    }
}
