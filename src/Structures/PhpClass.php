<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Structures;

use Murtukov\PHPCodeGenerator\Functions\Method;

class PhpClass extends OOPStructure
{
    private bool $isFinal = false;
    private bool $isAbstract = false;

    public function generate(): string
    {
        return <<<CLASS
        {$this->build()}class $this->name {$this->buildExtends()}{$this->buildImplements()}
        {
        {$this->buildContent()}
        }
        CLASS;
    }

    public static function create(string $name): self
    {
        return new self($name);
    }

    private function build(): string
    {
        $modifiers = '';

        if ($this->isFinal) {
            $modifiers .= 'final ';
        } elseif ($this->isAbstract) {
            $modifiers .= 'abstract ';
        }

        return $modifiers;
    }

    public function isFinal(): bool
    {
        return $this->isFinal;
    }

    public function setIsFinal(bool $isFinal): PhpClass
    {
        $this->isFinal = $isFinal;

        // Class cannot be final and abstract at the same time
        if ($isFinal) {
            $this->isAbstract = false;
        }

        return $this;
    }

    public function isAbstract(): bool
    {
        return $this->isAbstract;
    }

    public function setIsAbstract(bool $isAbstract): PhpClass
    {
        $this->isAbstract = $isAbstract;

        // Class cannot be final and abstract at the same time
        if (true === $isAbstract) {
            $this->isFinal = false;
        }

        return $this;
    }
}