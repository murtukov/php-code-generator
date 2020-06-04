<?php

namespace Murtukov\PHPCodeGenerator;

interface PropertyInterface
{
    public function generate(): string;

    public function getName(): string;

    public function setName(string $name): self;

    public function getModifier(): string;

    public function setPublic(): self;

    public function setPrivate(): self;

    public function setProtected(): self;

    public function getDefaulValue(): string;

    public function setDefaulValue($defaulValue, bool $isString = false): self;

    public function isStatic(): bool;

    public function setIsStatic(bool $isStatic): self;

    public function setIsConst(bool $isConst): self;
}
