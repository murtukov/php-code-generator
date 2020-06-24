<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class Block extends AbstractGenerator implements BlockInterface
{
    use ScopedContentTrait;

    public function generate(): string
    {
        return <<<CODE
        {
        {$this->generateContent()}
        }
        CODE;
    }

    public static function new()
    {
        return new static();
    }
}
