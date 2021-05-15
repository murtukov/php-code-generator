<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use function array_slice;
use function array_unshift;
use function end;
use function join;
use function rtrim;

trait ScopedContentTrait
{
    /**
     * @var object[]
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
        $this->content[] = $this->createNewLine($values);

        return $this;
    }

    /**
     * @param GeneratorInterface|string ...$values
     *
     * @return $this
     */
    public function prepend(...$values): self
    {
        array_unshift($this->content, $this->createNewLine($values));

        return $this;
    }

    /**
     * @return $this
     */
    public function emptyLine(): self
    {
        $this->content[] = '';

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

    public function getFirstLine(): ?object
    {
        return $this->content[0] ?? null;
    }

    public function getLastLine(): ?object
    {
        return end($this->content) ?: null;
    }

    public function getRange(int $start = 0, int $end = null): array
    {
        return array_slice($this->content, $start, $end);
    }

    protected function generateContent(): string
    {
        $content = '';

        if (!empty($this->content)) {
            $content = Utils::indent(join("\n", $this->content));
            $content = rtrim($content);
        }

        return $content;
    }

    /**
     * Generate content optionally wrapped with new lines.
     */
    protected function generateWrappedContent(string $left = "\n", string $right = "\n"): string
    {
        $content = $this->generateContent();

        if (!$content) {
            return '';
        }

        return $left.$content.$right;
    }

    private function createNewLine($values)
    {
        return new class($values) extends DependencyAwareGenerator
        {
            private array $parts;

            public function __construct(array $values)
            {
                $this->parts = $values;

                foreach ($values as $value) {
                    if ($value instanceof DependencyAwareGenerator) {
                        $this->dependencyAwareChildren[] = $value;
                    }
                }

                if (!end($values) instanceof BlockInterface) {
                    $this->parts[] = ';';
                }
            }

            public function generate(): string
            {
                return join($this->parts);
            }
        };
    }
}
