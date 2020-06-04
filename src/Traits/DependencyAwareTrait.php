<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Traits;

use function strrchr;
use function substr;

trait DependencyAwareTrait
{
    protected bool $shortenQualifiers = true;

    protected array $usePaths = [];

    /**
     * List of all generator children, which maintain their own use dependencies.
     * The list will be defined in the constructor.
     *
     * @var mixed[]
     */
    protected array $dependencyAwareChildren = [];

    /**
     * @param string $alias
     */
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

    /**
     * Returns all use-qualifiers used in this object and all it's children.
     *
     * @return string[]
     */
    public function getUsePaths(): array
    {
        $mergedPaths = $this->usePaths;

        foreach ($this->dependencyAwareChildren as $child) {
            if (is_array($child)) {
                foreach ($child as $subchild) {
                    $mergedPaths = array_replace($mergedPaths, $subchild->getUsePaths());
                }
            } else {
                $mergedPaths = array_replace($mergedPaths, $child->getUsePaths());
            }
        }

        return $mergedPaths;
    }
}
