<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class Comment extends AbstractGenerator implements BlockInterface
{
    public const TYPE_STAR = 'Star';
    public const TYPE_DOCBLOCK = 'DocBlock';
    public const TYPE_HASH = 'Hash';
    public const TYPE_SLASH = 'Slash';
    protected array $lines = [];

    final private function __construct(
        string $text = '',
        protected string $type = self::TYPE_STAR,
    ) {
        $this->addText($text);
    }

    /**
     * @return static
     */
    public static function block(string $text = ''): self
    {
        return new static($text, self::TYPE_STAR);
    }

    /**
     * @return static
     */
    public static function hash(string $text = ''): self
    {
        return new static($text, self::TYPE_HASH);
    }

    /**
     * @return static
     */
    public static function docBlock(string $text = ''): self
    {
        return new static($text, self::TYPE_DOCBLOCK);
    }

    /**
     * @return static
     */
    public static function slash(string $text = ''): self
    {
        return new static($text, self::TYPE_SLASH);
    }

    public function generate(): string
    {
        return $this->{"build$this->type"}();
    }

    private function buildStar(): string
    {
        $lines = implode("\n * ", $this->lines);

        return <<<CODE
        /*
         * $lines
         */
        CODE;
    }

    private function buildDocBlock(): string
    {
        $lines = implode("\n * ", $this->lines);

        return <<<CODE
        /**
         * $lines
         */
        CODE;
    }

    private function buildHash(): string
    {
        return '# '.implode("\n# ", $this->lines);
    }

    private function buildSlash(): string
    {
        return '// '.implode("\n// ", $this->lines);
    }

    /**
     * @return $this
     */
    public function addLine(string $text): self
    {
        $this->lines[] = $text;

        return $this;
    }

    /**
     * @return $this
     */
    public function addEmptyLine(): self
    {
        $this->lines[] = '';

        return $this;
    }

    /**
     * @return $this
     */
    public function addText(string $text): self
    {
        if ('' === $text) {
            return $this;
        }

        $parts = explode("\n", $text);
        $this->lines = [...$this->lines, ...$parts];

        return $this;
    }
}
