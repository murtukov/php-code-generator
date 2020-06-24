<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class Comment extends AbstractGenerator implements BlockInterface
{
    public const TYPE_STAR = 'Star';
    public const TYPE_DOCBLOCK = 'DocBlock';
    public const TYPE_HASH = 'Hash';
    public const TYPE_SLASH = 'Slash';

    protected string $type;
    protected array  $lines = [];

    private function __construct(string $text = '', string $type = self::TYPE_STAR)
    {
        $this->addText($text);
        $this->type = $type;
    }

    public static function block(string $text = ''): self
    {
        return new self($text, self::TYPE_STAR);
    }

    public static function hash(string $text = ''): self
    {
        return new self($text, self::TYPE_HASH);
    }

    public static function docBlock(string $text = ''): self
    {
        return new self($text, self::TYPE_DOCBLOCK);
    }

    public static function slash(string $text = ''): self
    {
        return new self($text, self::TYPE_SLASH);
    }

    public function generate(): string
    {
        return $this->{"build$this->type"}();
    }

    private function buildStar()
    {
        $lines = implode("\n * ", $this->lines);

        return <<<CODE
        /*
         * $lines
         */
        CODE;
    }

    private function buildDocBlock()
    {
        $lines = implode("\n * ", $this->lines);

        return <<<CODE
        /**
         * $lines
         */
        CODE;
    }

    private function buildHash()
    {
        return '# '.implode("\n# ", $this->lines);
    }

    private function buildSlash()
    {
        return '// '.implode("\n// ", $this->lines);
    }

    public function addLine(string $text): self
    {
        $this->lines[] = $text;

        return $this;
    }

    public function addEmptyLine(): self
    {
        $this->lines[] = '';

        return $this;
    }

    public function addText(string $text): self
    {
        $parts = explode("\n", $text);
        $this->lines = [...$this->lines, ...$parts];

        return $this;
    }
}
