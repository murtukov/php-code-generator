<?php

namespace Murtukov\PHPCodeGenerator;

interface PropertyInterface
{
    function generate(): string;
    function getName(): string;
    function setName(string $name): self;
    function getModifier(): string;
    function setPublic(): self;
    function setPrivate(): self;
    function setProtected(): self;
    function getDefaulValue(): string;
    function setDefaulValue($defaulValue, bool $isString = false): self;
    function isStatic(): bool;
    function setIsStatic(bool $isStatic): self;
    function setIsConst(bool $isConst): self;
}
