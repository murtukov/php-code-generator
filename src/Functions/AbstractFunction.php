<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Functions;

use Murtukov\PHPCodeGenerator\DependencyAwareGenerator;

abstract class AbstractFunction extends DependencyAwareGenerator
{
    public Signature $signature;

    public function getReturnType(): string
    {
        return $this->signature->getReturnType();
    }

    public function setReturnType(string $returnType): self
    {
        $this->signature->setReturnType($returnType);

        return $this;
    }

    public function getArgument(int $index): ?Argument
    {
        return $this->signature->getArgument($index);
    }

    public function removeArgument(int $index): self
    {
        $this->signature->removeArgument($index);

        return $this;
    }

    public function createArgument(string $name, string $type = '', $defaultValue = Argument::NO_PARAM): Argument
    {
        return $this->signature->createArgument($name, $type, $defaultValue);
    }

    public function addArgument(string $name, string $type = '', $defaultValue = Argument::NO_PARAM): self
    {
        $this->signature->addArgument($name, $type, $defaultValue);

        return $this;
    }

    public function add(FunctionMemberInterface $member): self
    {
        $this->add($member);

        return $this;
    }

    public function bindVar(string $name, bool $isByReference = false): self
    {
        $this->signature->bindVar($name, $isByReference);

        return $this;
    }

    public function removeUses()
    {
        $this->signature->removeUses();

        return $this;
    }

    public function isStatic(): bool
    {
        return $this->signature->isStatic;
    }

    public function setStatic(): self
    {
        $this->signature->isStatic = true;

        return $this;
    }

    public function unsetStatic()
    {
        $this->signature->isStatic = false;

        return $this;
    }
}