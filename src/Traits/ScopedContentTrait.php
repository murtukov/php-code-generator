<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Traits;

use Murtukov\PHPCodeGenerator\BlockInterface;
use Murtukov\PHPCodeGenerator\DependencyAwareGenerator;
use Murtukov\PHPCodeGenerator\GeneratorInterface;
use Murtukov\PHPCodeGenerator\Utils;
use function array_map;
use function array_unshift;
use function join;

trait ScopedContentTrait
{
    private array $content = [];
    private int $emptyLinesBuffer = 0;

    /**
     * @param GeneratorInterface|string ...$values
     */
    public function append(...$values): self
    {
        if (end($values) instanceof BlockInterface) {
            $this->content[] = [...$values];
        } else {
            $this->content[] = [...$values, ';'];
        }

        foreach ($values as $value) {
            if ($value instanceof DependencyAwareGenerator) {
                $this->dependencyAwareChildren[] = $value;
            }
        }

        return $this;
    }

    /**
     * @param GeneratorInterface|string ...$values
     */
    public function prepend(...$values): self
    {
        if (end($values) instanceof BlockInterface) {
            array_unshift($this->content, [...$values]);
        } else {
            array_unshift($this->content, [...$values, ';']);
        }

        foreach ($values as $value) {
            if ($value instanceof DependencyAwareGenerator) {
                $this->dependencyAwareChildren[] = $value;
            }
        }

        return $this;
    }

    public function emptyLine(): self
    {
        $this->content[] = [];

        return $this;
    }

    private function generateContent(): string
    {
        $content = '';

        if (!empty($this->content)) {
            $content = Utils::indent(join(
                "\n",
                array_map(fn($line) => join($line), $this->content)
            ));
        }

        return $content;
    }
}
