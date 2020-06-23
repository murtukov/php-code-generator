<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use Murtukov\PHPCodeGenerator\Functions\Method;
use function join;

class PhpClass extends OOPStructure
{
    protected string $extends    = '';
    protected bool   $isAbstract = false;
    protected bool   $isFinal    = false;
    protected array  $implements  = [];

    public function setExtends(string $fqcn): self
    {
        $this->extends = $this->resolveQualifier($fqcn);

        return $this;
    }

    public function addImplements(string ...$classNames): self
    {
        foreach ($classNames as $name) {
            $this->implements[] = $this->resolveQualifier($name);
        }

        return $this;
    }

    protected function buildImplements(): string
    {
        return !empty($this->implements) ? ' implements '.join(', ', $this->implements) : '';
    }

    protected function buildExtends(): string
    {
        if ($this->extends) {
            return " extends $this->extends";
        }

        return '';
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param mixed $value
     */
    public function addConst(string $name, $value, string $modifier = Modifier::PUBLIC): self
    {
        return $this->append(Property::new($name, $modifier, '', $value)->setConst());
    }

    public function addProperty(string $name, string $modifier = Modifier::PUBLIC, string $type = '', $defaulValue = ''): self
    {
        return $this->append(new Property($name, $modifier, $type, $defaulValue));
    }

    public function addMethod(string $name, string $modifier = 'public', string $returnType = ''): self
    {
        return $this->append(new Method($name, $modifier, $returnType));
    }

    public function addConstructor(string $modifier = 'public'): self
    {
        return $this->append(new Method('__construct', $modifier, ''));
    }

    public function generate(): string
    {
        return <<<CODE
        {$this->buildDocBlock()}{$this->buildPrefix()}class $this->name{$this->buildExtends()}{$this->buildImplements()}
        {
        {$this->generateContent()}
        }
        CODE;
    }

    private function buildPrefix(): string
    {
        $prefix = '';

        if ($this->isFinal) {
            $prefix .= 'final ';
        } elseif ($this->isAbstract) {
            $prefix .= 'abstract ';
        }

        return $prefix;
    }

    public function isFinal(): bool
    {
        return $this->isFinal;
    }

    public function setFinal(): self
    {
        $this->isFinal = true;

        // Class cannot be final and abstract at the same time
        $this->isAbstract = false;

        return $this;
    }

    public function unsetFinal(): self
    {
        $this->isFinal = false;

        return $this;
    }

    public function isAbstract(): bool
    {
        return $this->isAbstract;
    }

    public function setAbstract(): self
    {
        $this->isAbstract = true;

        // Class cannot be final and abstract at the same time
        $this->isFinal = false;

        return $this;
    }

    public function unsetAbstract(): self
    {
        $this->isAbstract = false;

        return $this;
    }
}
