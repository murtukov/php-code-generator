<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use Murtukov\PHPCodeGenerator\Traits\ScopedContentTrait;

class Block extends AbstractGenerator
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
        return new self();
    }
}