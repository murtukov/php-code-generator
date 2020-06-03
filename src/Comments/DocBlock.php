<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Comments;

class DocBlock extends Comment
{
    public function generate(): string
    {
        $lines = implode("\n * ", $this->lines);

        return <<<CODE
        /**
         * $lines
         */
        CODE;
    }
}