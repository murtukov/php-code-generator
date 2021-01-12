<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class Qualifier extends DependencyAwareGenerator
{
    private string $name;

    public final function __construct(string $className)
    {
        $this->name = $this->resolveQualifier($className);
    }

    public static function new(string $className): self
    {
        return new static($className);
    }

    public function generate(): string
    {
        return $this->name;
    }
}
