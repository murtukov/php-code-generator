<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class Loop extends DependencyAwareGenerator implements BlockInterface
{
    use ScopedContentTrait;

    public const TYPE_WHILE = 'while';
    public const TYPE_FOR = 'for';
    public const TYPE_FOREACH = 'foreach';
    public const TYPE_DO_WHILE = 'doWhile';

    final public function __construct(
        private readonly string $condition = '',
        private readonly string $type = self::TYPE_WHILE,
    ) {
        $this->dependencyAwareChildren = [&$this->content];
    }

    public function generate(): string
    {
        // do ... while
        if (self::TYPE_DO_WHILE === $this->type) {
            return <<<CODE
            do {
            {$this->generateContent()}
            } while ($this->condition)
            CODE;
        }

        // Other loop types
        return <<<CODE
        $this->type ($this->condition) {
        {$this->generateContent()}
        }
        CODE;
    }

    public static function while(string $condition): self
    {
        return new static($condition);
    }

    public static function for(string $condition): self
    {
        return new static($condition, self::TYPE_FOR);
    }

    public static function foreach(string $condition): self
    {
        return new static($condition, self::TYPE_FOREACH);
    }

    /**
     * @return static
     */
    public static function doWhile(string $condition): self
    {
        return new static($condition, self::TYPE_DO_WHILE);
    }
}
