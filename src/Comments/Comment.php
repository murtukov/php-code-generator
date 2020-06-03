<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Comments;

use Murtukov\PHPCodeGenerator\AbstractGenerator;

class Comment extends AbstractGenerator
{
    protected array $lines = [];

    public function __construct(string $text = '')
    {
        if ($text) {
            $this->addText($text);
        }
    }
    
    public static function docBlock(string $text = '')
    {
        return (new self($text))->set;
    }

    public function generate(): string
    {
        $lines = implode("\n * ", $this->lines);

        return <<<CODE
        /*
         * $lines
         */
        CODE;
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