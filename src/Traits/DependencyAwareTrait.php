<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Traits;

use function strrchr;
use function substr;

trait DependencyAwareTrait
{
    private bool $shortenQualifiers = true;
    private array $usePaths = [];

/*    public function addUsePath(string $fqcn, string $alias): self
    {
        $this->usePaths[$fqcn] = $alias;

        return $this;
    }

    public function removeUsePath(string $fqcn): self
    {
        unset($this->usePaths[$fqcn]);

        return $this;
    }*/

    public function resolveQualifier(string $path, $alias = ''): string
    {
        if (!empty($path) && '\\' === $path[0]) {
            return $path;
        }

        $this->usePaths[$path] = $alias;

        $portion = strrchr($path, '\\');

        if ($portion) {
            $path = substr($portion, 1);
        }

        return $path;
    }

    public function getUsePaths(): array
    {
        return $this->usePaths;
    }
}