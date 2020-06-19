<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

final class Modifier
{
    public const NONE      = '';
    public const PUBLIC    = 'public';
    public const PROTECTED = 'protected';
    public const PRIVATE   = 'private';

    private function __construct()
    {
    }
}