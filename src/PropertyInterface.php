<?php

namespace Murtukov\PHPCodeGenerator;

interface PropertyInterface
{
    function generate(): string;
    function getName(): string;
    function setName(string $name): void;
    function getModifier(): string;
    function setModifier(string $modifier): void;
    function getDefaulValue(): string;
    function setDefaulValue(string $defaulValue): void;
    function isStatic(): bool;
    function setIsStatic(bool $isStatic): void;
}
