<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

abstract class AbstractFunction extends DependencyAwareGenerator
{
    public Signature $signature;

    public function getReturnType(): string
    {
        return $this->signature->getReturnType();
    }

    /**
     * @return $this
     */
    public function setReturnType(string $returnType): self
    {
        $this->signature->setReturnType($returnType);

        return $this;
    }

    public function getArgument(int $index = 1): ?Argument
    {
        return $this->signature->getArgument($index);
    }

    /**
     * @return $this
     */
    public function removeArgument(int $index): self
    {
        $this->signature->removeArgument($index);

        return $this;
    }

    /**
     * @return $this
     */
    public function removeArguments(): self
    {
        $this->signature->removeArguments();

        return $this;
    }

    /**
     * @param mixed $defaultValue
     */
    public function createArgument(string $name, string $type = '', $defaultValue = Argument::NO_PARAM): Argument
    {
        return $this->signature->createArgument($name, $type, $defaultValue);
    }

    /**
     * @param mixed $defaultValue
     *
     * @return $this
     */
    public function addArgument(string $name, string $type = '', $defaultValue = Argument::NO_PARAM): self
    {
        $this->signature->addArgument($name, $type, $defaultValue);

        return $this;
    }

    /**
     * @return $this
     */
    public function addArguments(string ...$names): self
    {
        $this->signature->addArguments(...$names);

        return $this;
    }

    /**
     * @return $this
     */
    public function add(FunctionMemberInterface $member): self
    {
        $this->signature->add($member);

        return $this;
    }

    /**
     * @return $this
     */
    public function bindVar(string $name, bool $isByReference = false): self
    {
        $this->signature->bindVar($name, $isByReference);

        return $this;
    }

    /**
     * @return $this
     */
    public function bindVars(string ...$names): self
    {
        $this->signature->bindVars(...$names);

        return $this;
    }

    /**
     * @return $this
     */
    public function removeBindVars(): self
    {
        $this->signature->removeBindVars();

        return $this;
    }

    public function isStatic(): bool
    {
        return $this->signature->isStatic;
    }

    /**
     * @return $this
     */
    public function setStatic(): self
    {
        $this->signature->isStatic = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function unsetStatic()
    {
        $this->signature->isStatic = false;

        return $this;
    }
}
