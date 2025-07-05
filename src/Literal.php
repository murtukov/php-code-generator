<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use function sprintf;

class Literal extends DependencyAwareGenerator
{
    private array $values;

    final public function __construct(
        private readonly string $format,
        GeneratorInterface ...$values,
    ) {
        $this->values = $values ?? [];
        $this->dependencyAwareChildren = [$this->values];
    }

    /**
     * @return static
     */
    public static function new(string $format, GeneratorInterface ...$values): self
    {
        return new static($format, ...$values);
    }

    public function generate(): string
    {
        return sprintf($this->format, ...$this->values);
    }
}
