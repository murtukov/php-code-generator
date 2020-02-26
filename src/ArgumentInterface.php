<?php


namespace Murtukov\PHPCodeGenerator;


interface ArgumentInterface
{
    public function isSpread(): bool;
    public function setIsSpread(bool $isSpread): ArgumentInterface;
    public function isByReference(): bool;
    public function setIsByReference(bool $isByReference): ArgumentInterface;
    public function setType(string $type): self;
    public function setDefaultValue($value): self;
}