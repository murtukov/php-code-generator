<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use function array_map;
use function array_unshift;
use function count;
use function end;
use function join;
use function rtrim;

trait ScopedContentTrait
{
    /**
     * @var array[]
     */
    protected array $content = [];
    private int $emptyLinesBuffer = 0;
    protected array $dependencyAwareChildren = [];

    /**
     * @param GeneratorInterface|string ...$values
     *
     * @return $this
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
     *
     * @return $this
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

    /**
     * @return array|null
     */
    public function getLastLine(): ?array
    {
        $length = count($this->content);

        if ($length > 0) {
            return $this->content[--$length];
        }

        return null;
    }

    protected function generateContent(): string
    {
        $content = '';

        if (!empty($this->content)) {
            $content = Utils::indent(join("\n", array_map(fn ($line) => join('', $line), $this->content)));
            $content = rtrim($content);
        }

        return $content;
    }

    /**
     * Generate content wrapped with new lines.
     */
    protected function generateWrappedContent(string $left = "\n", string $right = "\n"): string
    {
        $content = $this->generateContent();

        if (!$content) {
            return '';
        }

        return $left . $content . $right;
    }
}
