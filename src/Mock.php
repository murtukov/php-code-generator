<?php

namespace Murtukov\PHPCodeGenerator;

/**
 * Helper class for conditional builders.
 *
 * @method addItem(string $key, mixed $value)
 */
class Mock
{
    private static Collection $caller;
    private static self   $instance;

    public static function getInstance(Collection $caller): self
    {
        self::$caller = $caller;

        return self::$instance ?? self::$instance = new self();
    }

    public function __call(string $name, array $arguments): Collection
    {
        return self::$caller;
    }
}
