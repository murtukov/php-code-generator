<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use function join;

class PhpClass extends OOPStructure
{
    protected string $extends = '';
    protected bool $isAbstract = false;
    protected bool $isFinal = false;
    protected array $implements = [];
    protected Method $constructor;

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

    public function removeImplements(): self
    {
        $this->implements = [];

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
     * @return $this
     */
    public function addConst(string $name, mixed $value, Modifier $modifier = Modifier::PUBLIC): self
    {
        return $this->append(Property::new($name, $modifier, '', $value)->setConst());
    }

    /**
     * @return $this
     */
    public function addProperty(string $name, Modifier $modifier = Modifier::PUBLIC, string $type = '', mixed $defaulValue = ''): self
    {
        return $this->append(new Property($name, $modifier, $type, $defaulValue));
    }

    public function addMethod(string $name, Modifier $modifier = Modifier::PUBLIC, string $returnType = ''): self
    {
        return $this
            ->append(new Method($name, $modifier, $returnType))
            ->emptyLine()
        ;
    }

    public function createMethod(string $name, Modifier $modifier = Modifier::PUBLIC, string $returnType = ''): Method
    {
        $method = new Method($name, $modifier, $returnType);

        $this->append($method)->emptyLine();

        return $method;
    }

    public function createConstructor(Modifier $modifier = Modifier::PUBLIC): Method
    {
        $this->constructor = new Method('__construct', $modifier, '');

        $this->append($this->constructor)->emptyLine();

        return $this->constructor;
    }

    public function addPromotedProperty(
        string $name,
        Modifier $modifier = Modifier::PUBLIC,
        string $type = '', 
        mixed $defaultValue = Argument::NO_PARAM
    ): self {
        if (empty($this->constructor)) {
            $this->createConstructor();
            $this->constructor->signature->setMultiline();
        }

        $this->constructor->addArgument($name, $type, $defaultValue, $modifier);

        return $this;
    }

    public function generate(): string
    {
        $content = $this->generateWrappedContent("\n", '');

        return <<<CODE
        {$this->buildDocBlock()}{$this->buildPrefix()}class $this->name{$this->buildExtends()}{$this->buildImplements()}
        {{$content}
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

    /**
     * @return $this
     */
    public function unsetFinal(): self
    {
        $this->isFinal = false;

        return $this;
    }

    public function isAbstract(): bool
    {
        return $this->isAbstract;
    }

    /**
     * @return $this
     */
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
