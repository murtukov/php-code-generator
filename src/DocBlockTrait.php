<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

trait DocBlockTrait
{
    protected ?Comment $docBlock = null;

    public function getDocBlock(): Comment
    {
        return $this->docBlock;
    }

    public function setDocBlock(Comment $docBlock): self
    {
        $this->docBlock = $docBlock;

        return $this;
    }

    public function createDocBlock(string $text): Comment
    {
        return $this->docBlock = Comment::docBlock($text);
    }

    public function addDocBlock(string $text): self
    {
        $this->docBlock = Comment::docBlock($text);

        return $this;
    }

    protected function buildDocBlock()
    {
        $code = '';

        if (null !== $this->docBlock) {
            $code = "$this->docBlock\n";
        }

        return $code;
    }
}