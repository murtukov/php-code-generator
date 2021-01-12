<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class Block extends AbstractGenerator implements BlockInterface
{
    use ScopedContentTrait;

    public final function __construct()
    {
    }

    public function generate(): string
    {
        return <<<CODE
        {
        {$this->generateContent()}
        }
        CODE;
    }

    /**
     * @return static
     */
    public static function new(): self
    {
        return new static();
    }
}
