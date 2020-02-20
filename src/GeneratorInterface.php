<?php

namespace Murtukov\PHPCodeGenerator;

interface GeneratorInterface
{
    public function generate(): string;
    public function __toString(): string;
}