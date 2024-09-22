<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\ConverterInterface;

class PrefixConverter implements ConverterInterface
{
    public function convert(mixed $value): string
    {
        return ltrim($value, 'pre_');
    }

    public function check(mixed $value): bool
    {
        if (is_string($value) && str_starts_with($value, 'pre_')) {
            return true;
        }

        return false;
    }
}
