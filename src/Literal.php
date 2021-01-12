<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class Literal extends AbstractGenerator
{
    private string $value;

    public final function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return static
     */
    public static function new(string $value): self
    {
        return new static($value);
    }

    public function generate(): string
    {
        return $this->value;
    }
}
