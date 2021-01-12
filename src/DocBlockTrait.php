<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

trait DocBlockTrait
{
    protected ?Comment $docBlock = null;

    public function getDocBlock(): ?Comment
    {
        return $this->docBlock;
    }

    /**
     * @return $this
     */
    public function setDocBlock(string $text): self
    {
        $this->docBlock = Comment::docBlock($text);

        return $this;
    }

    public function createDocBlock(string $text = ''): Comment
    {
        return $this->docBlock = Comment::docBlock($text);
    }

    /**
     * @return $this
     */
    public function removeDocBlock(): self
    {
        $this->docBlock = null;

        return $this;
    }

    protected function buildDocBlock(): string
    {
        $code = '';

        if (null !== $this->docBlock) {
            $code = "$this->docBlock\n";
        }

        return $code;
    }
}
