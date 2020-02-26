<?php

namespace Murtukov\PHPCodeGenerator\Traits;

trait DependencyAwareTrait
{
    /** @var string[] */
    private array $usePaths = [];


    public function addUseFQCN(string $fqcn, string $alias = ''): self
    {
        if (empty($this->usePaths[$fqcn])) {
            $this->usePaths[$fqcn] = 1;
        } else {
            $this->usePaths[$fqcn]++;
        }

        return $this;
    }

    public function removeUseClass(string $fqcn): self
    {
        if (isset($this->usePaths[$fqcn])) {
            unset($this->usePaths[$fqcn]);
        }

        return $this;
    }

    public function getUsePaths(): array
    {
        return $this->usePaths;
    }

    /**
     * Checks whether the given string is a FCQN. If yes, it will be added
     * into the collection of use paths and the class name without its prefix
     * will be returned.
     *
     * @param string $type
     * @param string $alias
     * @return false|string
     */
    public function addUseIfNecessary(string $type, string $alias = '')
    {
        // Check if type is a FQCN
        $className = substr(strrchr($type, '\\'), 1);

        if (!empty($className)) {
            // Add FQCN to use statements
            $this->addUseFQCN($type, $alias);
            return $className;
        }

        return $type;
    }
}