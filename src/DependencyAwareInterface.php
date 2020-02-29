<?php

namespace Murtukov\PHPCodeGenerator;

interface DependencyAwareInterface
{
    function getUsePaths(bool $recursive = true): array;
}