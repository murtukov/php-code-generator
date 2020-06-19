<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

abstract class OOPStructure extends DependencyAwareGenerator
{
    use DocBlockTrait;

    protected string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function new(string $name): self
    {
        return new static($name);
    }
}