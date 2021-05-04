<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class Block extends AbstractGenerator implements BlockInterface
{
    use ScopedContentTrait;

    final public function __construct()
    {
    }

    public function generate(): string
    {
        return <<<CODE
        {{$this->generateWrappedContent()}}
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
