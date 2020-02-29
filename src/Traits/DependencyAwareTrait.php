<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Traits;

trait DependencyAwareTrait
{
    private bool $shortenQualifiers = true;
    private array $usePaths = [];

    public function addUsePath(string $fqcn, string $alias): self
    {
        $this->usePaths[$fqcn] = $alias;

        return $this;
    }

    public function removeUsePath(string $fqcn): self
    {
        unset($this->usePaths[$fqcn]);

        return $this;
    }
}