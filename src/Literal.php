<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use function sprintf;

class Literal extends DependencyAwareGenerator
{
    private string $format;

    private array $values;

    public final function __construct(string $format, GeneratorInterface ...$values)
    {
        $this->format = $format;
        $this->values = $values ?? [];
    }

    /**
     * @return static
     */
    public static function new(string $format, GeneratorInterface ...$values): self
    {
        return new static($format, ...$values);
    }

    public function useGroupsToArray(): array
    {
        $useGroups = parent::useGroupsToArray();
        foreach ($this->values as $value) {
            if ($value instanceof DependencyAwareGenerator) {
                $useGroups += $value->useGroupsToArray();
            }
        }

        return $useGroups;
    }

    public function getUsePaths(): array
    {
        $usePaths = parent::getUsePaths();
        foreach ($this->values as $value) {
            if ($value instanceof DependencyAwareGenerator) {
                $usePaths += $value->getUsePaths();
            }
        }

        return $usePaths;
    }

    public function generate(): string
    {
        return sprintf($this->format, ...$this->values);
    }
}
