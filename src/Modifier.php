<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

enum Modifier: string
{
    case NONE = '';
    case PUBLIC = 'public';
    case PROTECTED = 'protected';
    case PRIVATE = 'private';
}
