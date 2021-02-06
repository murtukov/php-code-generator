<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use function array_map;
use function array_unshift;
use function join;

trait ScopedContentTrait
{
    private array $content = [];
    private int $emptyLinesBuffer = 0;
    protected array $dependencyAwareChildren = [];

    /**
     * @param GeneratorInterface|string ...$values
     *
     * @return $this
     */
    public function append(...$values): self
    {
        $this->content[] = $this->createExpressionOrBlock($values);;

        foreach ($values as $value) {
            if ($value instanceof DependencyAwareGenerator) {
                $this->dependencyAwareChildren[] = $value;
            }
        }

        return $this;
    }

    /**
     * @param GeneratorInterface|string ...$values
     *
     * @return $this
     */
    public function prepend(...$values): self
    {
        array_unshift($this->content, $this->createExpressionOrBlock($values));

        foreach ($values as $value) {
            if ($value instanceof DependencyAwareGenerator) {
                $this->dependencyAwareChildren[] = $value;
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function emptyLine(): self
    {
        $this->content[] = [];

        return $this;
    }

    /**
     * @return $this
     */
    public function clearContent(): self
    {
        $this->content = [];

        return $this;
    }

    public function countContentBlocks(): int
    {
        return count($this->content);
    }

    /**
     * @param int                       $index
     * @param GeneratorInterface|string ...$values
     *
     * @return $this
     */
    public function insertBefore(int $index, ...$values): self
    {
        $values = $this->createExpressionOrBlock($values);

        array_splice($this->content, $index, 0, [$values]);

        return $this;
    }

    private function createExpressionOrBlock(array $values): array
    {
        if (end($values) instanceof BlockInterface) {
            return [...$values];
        }

        return [...$values, ';'];
    }

    /**
     * @param int                       $index
     * @param GeneratorInterface|string ...$values
     *
     * @return $this
     */
    public function insertAfter(int $index, ...$values): self
    {
        return $this->insertBefore(++$index, ...$values);
    }

    /**
     * @param int $index
     *
     * @return $this
     */
    public function remove(int $index): self
    {
        array_splice($this->content, $index, 1);

        return $this;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    protected function generateContent(): string
    {
        $content = '';

        if (!empty($this->content)) {
            $content = Utils::indent(join(
                "\n",
                array_map(fn ($line) => join('', $line), $this->content)
            ));
        }

        return rtrim($content);
    }
}
