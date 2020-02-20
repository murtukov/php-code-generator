<?php


namespace Murtukov\PHPCodeGenerator;


class Closure implements GeneratorInterface
{

    public function generate(): string
    {
        // TODO: Implement generate() method.
    }

    public function __toString(): string
    {
        return $this->generate();
    }
}