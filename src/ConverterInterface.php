<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

interface ConverterInterface
{
    public const TYPE_STRING = 'string';
    public const TYPE_INT = 'integer';
    public const TYPE_BOOL = 'boolean';
    public const TYPE_DOUBLE = 'double';
    public const TYPE_OBJECT = 'object';
    public const TYPE_ARRAY = 'array';

    public function convert($value);

    /**
     * Checks, whether the value should be converted.
     */
    public function check($value): bool;
}
