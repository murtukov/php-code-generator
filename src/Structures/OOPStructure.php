<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Structures;

use Murtukov\PHPCodeGenerator\AbstractGenerator;
use Murtukov\PHPCodeGenerator\Functions\Method;
use Murtukov\PHPCodeGenerator\PropertyInterface;
use Murtukov\PHPCodeGenerator\Traits\DependencyAwareTrait;
use Murtukov\PHPCodeGenerator\Traits\IndentableTrait;

abstract class OOPStructure extends AbstractGenerator
{
    use IndentableTrait;
    use DependencyAwareTrait;

    /** @var array[] */
    private array $constants = [];

    /** @var array[] */
    private array $staticProps = [];

    /** @var Method[] */
    private array $methods = [];

    /** @var string[] */
    protected array $implements = [];

    /** @var array[] */
    protected array $props = [];

    protected string  $extends = '';
    protected string  $name;


    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function setExtends(string $fqcn): self
    {
        $this->extends = $fqcn;

        return $this;
    }

    public function addImplement(string $name): self
    {
        if ($this->shortenQualifiers) {

        }

        $this->implements[] = $name;

        return $this;
    }

    protected function buildImplements(): string
    {
        return count($this->implements) > 0 ? 'implements ' . implode(', ', $this->implements) : '';
    }

    protected function buildExtends()
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

    public function createProperty(string $name, string $modifier = 'public', string $defaultValue = ''): PropertyInterface
    {
        return $this->props[] = self::newProperty($name, $modifier, $defaultValue);
    }

    public function createMethod(string $name, string $modifier = 'public', string $returnType = ''): Method
    {
        return $this->methods[] = new Method($name, $modifier, $returnType);
    }

    public function createConstructor(string $modifier = 'public', string $returnType = ''): Method
    {
        return $this->methods[] = new Method('__constructor', $modifier, $returnType);
    }

    protected function buildContent(): string
    {
        $code = implode("\n", $this->props);
        $code .= "\n\n";
        $code .= implode("\n\n", $this->methods);

        return $this->indent($code);
    }

    private function getDeclare(): string
    {
        return '';
    }

    /**
     * Inner class for properties
     *
     * @param string $name
     * @param string $modifier
     * @param string $defaultValue
     * @return PropertyInterface
     */
    private static function newProperty(string $name, string $modifier = 'public', string $defaultValue = '')
    {
        return new class($name, $modifier, $defaultValue) extends AbstractGenerator implements PropertyInterface {
            private string  $name;
            private string  $modifier;
            private string  $defaulValue = '';
            private bool    $isStatic = false;
            private bool    $isConst = false;

            public function __construct(string $name, string $modifier, string $defaulValue = '', bool $isStatic = false)
            {
                $this->name = $name;
                $this->modifier = $modifier ?? 'public';
                $this->defaulValue = $defaulValue;
                $this->isStatic = $isStatic;
            }

            public function generate(): string
            {
                if ($this->isConst) {
                    return "$this->modifier const $this->name{$this->compileDefaultValue()};";
                } elseif ($this->isStatic) {
                    return "$this->modifier static $$this->name{$this->compileDefaultValue()};";
                }

                return "$this->modifier $$this->name{$this->compileDefaultValue()};";
            }

            private function compileDefaultValue(): string
            {
                return $this->defaulValue ? " = $this->defaulValue" : '';
            }

            public function getName(): string
            {
                return $this->name;
            }

            public function setName(string $name): self
            {
                $this->name = $name;
                return $this;
            }

            public function getModifier(): string
            {
                return $this->modifier;
            }

            public function getDefaulValue(): string
            {
                return $this->defaulValue;
            }

            public function setDefaulValue($defaulValue, bool $isString = false): self
            {
                if (is_array($defaulValue)) {
                    $this->defaulValue = '[]';
                } else {
                    $this->defaulValue = $isString ? "'$defaulValue'" : $defaulValue;
                }

                return $this;
            }

            public function isStatic(): bool
            {
                return $this->isStatic;
            }

            public function setIsStatic(bool $isStatic): self
            {
                $this->isStatic = $isStatic;
                return $this;
            }

            public function setIsConst(bool $isConst): self
            {
                $this->isConst = $isConst;
                return $this;
            }

            function setPublic(): self
            {
                $this->modifier = 'public';
                return $this;
            }

            function setPrivate(): self
            {
                $this->modifier = 'private';
                return $this;
            }

            function setProtected(): PropertyInterface
            {
                $this->modifier = 'protected';
                return $this;
            }

        };
    }
}