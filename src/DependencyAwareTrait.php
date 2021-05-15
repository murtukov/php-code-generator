<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

trait DependencyAwareTrait
{
    protected array $usePaths = [];
    protected array $useGroups = [];

    /**
     * List of all generator children, which maintain their own use dependencies.
     * The list should be defined in the constructor.
     *
     * @var mixed[]
     */
    protected array $dependencyAwareChildren = [];

    public function resolveQualifier(string $path, string $alias = ''): string
    {
        if (empty($path) || false === Config::$shortenQualifiers || '\\' === $path[0]) {
            return $path;
        }

        if ($path[0] === Config::$suppressSymbol) {
            return substr($path, 1);
        }

        if ($qualifier = Utils::resolveQualifier($path)) {
            $this->usePaths[$path] = $alias;
            $path = $qualifier;
        }

        return $path;
    }

    /**
     * @return $this
     */
    public function addUse(string $fqcn, string ...$aliases): self
    {
        $this->usePaths[$fqcn] = implode(', ', $aliases);

        return $this;
    }

    /**
     * @return $this
     */
    public function addUseGroup(string $fqcn, string ...$classNames)
    {
        foreach ($classNames as $name) {
            if ($qualifier = Utils::resolveQualifier($name)) {
                $name = $qualifier;
            }

            if (empty($this->useGroups[$fqcn]) || !in_array($name, $this->useGroups[$fqcn])) {
                $this->useGroups[$fqcn][] = $name;
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removeUse(string $fqcn): self
    {
        unset($this->usePaths[$fqcn]);
        unset($this->useGroups[$fqcn]);

        return $this;
    }

    public function useGroupsToArray(): array
    {
        $result = [];

        foreach ($this->useGroups as $path => $classNames) {
            $result[rtrim($path, '\\').'\{'.implode(', ', $classNames).'}'] = '';
        }

        return $result;
    }

    /**
     * Returns all use-qualifiers used in this object and all it's children.
     *
     * @return string[]
     */
    public function getUsePaths(): array
    {
        // Merge self use paths and use groups
        $mergedPaths = $this->usePaths + $this->useGroupsToArray();

        foreach ($this->dependencyAwareChildren as $child) {
            if (is_array($child)) {
                foreach ($child as $subchild) {
                    if ($subchild instanceof self) {
                        $mergedPaths = $mergedPaths + $subchild->getUsePaths();
                    }
                }
            } else {
                $mergedPaths = $mergedPaths + $child->getUsePaths();
            }
        }

        return $mergedPaths;
    }
}