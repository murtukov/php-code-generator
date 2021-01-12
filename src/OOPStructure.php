<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

abstract class OOPStructure extends DependencyAwareGenerator
{
    use ScopedContentTrait;
    use DocBlockTrait;

    public string $name;

    public final function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return static
     */
    public static function new(string $name): self
    {
        return new static($name);
    }
}
