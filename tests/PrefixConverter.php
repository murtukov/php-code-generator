<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\ConverterInterface;

class PrefixConverter implements ConverterInterface
{
    public function convert($value)
    {
        return ltrim($value, 'pre_');
    }

    public function check($string): bool
    {
        if (\is_string($string) && 'pre_' === substr($string, 0, 4)) {
            return true;
        }

        return false;
    }
}
