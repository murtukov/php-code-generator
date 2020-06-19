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
    protected array  $traits     = [];

    /** @var Method[] */
    protected array $methods = [];

    /** @var string[] */
    protected array $implements = [];

    /** @var Property[] */
    protected array $props = [];

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

    public function addTraits(string ...$traits): self
    {
        foreach ($traits as $trait) {
            $this->traits[] = $this->resolveQualifier($trait);
        }

        return $this;
    }

    protected function buildImplements(): string
    {
        return !empty($this->implements) ? 'implements '.join(', ', $this->implements) : '';
    }

    protected function buildExtends(): string
    {
        if ($this->extends) {
            return "extends $this->extends ";
        }

        return '';
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param mixed $defaultValue
     */
    public function createProperty(string $name, string $modifier = Property::PUBLIC, string $type = '', $defaultValue = ''): Property
    {
        return $this->props[] = new Property($name, $modifier, $type, $defaultValue);
    }

    public function createConst(string $name, string $value, string $modifier = 'public'): Property
    {
        return $this->createProperty($name, $modifier, '', $value)->setConst();
    }

    /**
     * @param mixed $value
     */
    public function addConst(string $name, $value, string $modifier = 'public'): self
    {
        $this->createProperty($name, $modifier, '', $value)->setConst();

        return $this;
    }

    public function addProperty(string $name, string $modifier = Property::PUBLIC, string $type = '', $defaulValue = ''): self
    {
        $this->props[] = new Property($name, $modifier, $type, $defaulValue);

        return $this;
    }

    public function createMethod(string $name, string $modifier = Property::PUBLIC, string $returnType = ''): Method
    {
        return $this->methods[] = new Method($name, $modifier, $returnType);
    }

    public function addMethod(string $name, string $modifier = 'public', string $returnType = ''): self
    {
        $this->methods[] = new Method($name, $modifier, $returnType);

        return $this;
    }

    public function createConstructor(string $modifier = 'public'): Method
    {
        return $this->methods[] = new Method('__construct', $modifier, '');
    }

    public function generate(): string
    {
        $code  = 'use ' . join(", ", $this->traits);
        $code .= join("\n", $this->props);

        if (!empty($code)) {
            $code .= "\n\n";
        }

        $code .= join("\n\n", $this->methods);

        $content = Utils::indent($code);

        return <<<CODE
        {$this->buildDocBlock()}{$this->buildPrefix()}class $this->name {$this->buildExtends()}{$this->buildImplements()}
        {
        {$content}
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
