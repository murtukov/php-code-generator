<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use Murtukov\PHPCodeGenerator\Traits\IndentableTrait;
use function array_pop;
use function count;
use function explode;
use function implode;
use function in_array;
use function str_replace;

class PHPClass implements GeneratorInterface
{
    use IndentableTrait;

    /** @var array[] */
    private array $constants = [];

    /** @var array[] */
    private array $staticProps = [];

    /** @var array[] */
    private array $props = [];

    /** @var Method[] */
    private array $methods = [];

    /** @var string[] */
    private array $useStatements = [];

    /** @var string[] */
    private array $implements = [];

    /** @var string[] */
    private array $declares;

    /** @var string[] */
    private array $consts = [];

    private string  $namespace = '';
    private string  $extends = '';
    private bool    $isFinal = false;
    private bool    $isAbstract = false;
    private string  $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function generate(): string
    {
        return <<<CODE
        <?php 
        {$this->generateNamespace()}{$this->generateUseStatements()}
        {$this->generatePrefix()}class $this->name {$this->generateExtends()}{$this->generateImplements()}
        {
        {$this->generateContent()}
        }
        CODE;
    }

    public function __toString(): string
    {
        return $this->generate();
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function addMethod(Method $method): self
    {
        $this->methods[] = $method;

        return $this;
    }

    public function createMethod(string $name, string $modifier = 'public', string $returnType = ''): Method
    {
        return $this->methods[] = new Method($name, $modifier, $returnType);
    }

    public function addImplements(string $fqcn): self
    {
        $parts = explode('\\', $fqcn);
        $className = array_pop($parts);

        if ($this->hasUseStatementAlias($className)) {
            $this->implements[] = $fqcn;
        } else {
            $this->addUseStatement($fqcn);
            // todo: make unique
            // todo: remove classname from use statements array by removing the class name of 'implements'
            $this->implements[] = $className;
        }

        return $this;
    }

    public function setExtends(string $fqcn): self
    {
        $parts = explode('\\', $fqcn);
        $className = array_pop($parts);

        if ($this->hasUseStatementAlias($className)) {
            $this->extends = $fqcn;
        } else {
            $this->addUseStatement($fqcn);
            // todo: remove classname from use statements array by change of 'extends'
            $this->extends = $className;
        }

        return $this;
    }

    public function addUseStatement(string $fqcn, string $alias = ''): self
    {
        $this->useStatements[$fqcn] = $alias;

        return $this;
    }

    public function generateUseStatements(): string
    {
        $code = '';

        if (count($this->useStatements) > 0) {
            $code = "\n";
            foreach ($this->useStatements as $stm => $as) {
                $code .= "use $stm";

                if ($as) {
                    $code .= " as $as";
                }

                $code .= ";\n";
            }
        }

        return $code;
    }

    private function generateContent(): string
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

    private function generateImplements(): string
    {
        return count($this->implements) > 0 ? 'implements ' . implode(', ', $this->implements) : '';
    }

    private function generatePrefix(): string
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

    public function setIsFinal(bool $isFinal): PHPClass
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

    public function setIsAbstract(bool $isAbstract): PHPClass
    {
        $this->isAbstract = $isAbstract;

        // Class cannot be final and abstract at the same time
        if (true === $isAbstract) {
            $this->isFinal = false;
        }

        return $this;
    }

    private function generateNamespace(): string
    {
        if ($this->namespace) {
            return "\nnamespace $this->namespace;\n";
        }

        return '';
    }

    public function hasUseStatementAlias(string $alias): bool
    {
        if (in_array($alias, $this->useStatements)) {
            return true;
        }

        return false;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): PHPClass
    {
        $this->namespace = $namespace;
        return $this;
    }

    private function generateExtends()
    {
        if ($this->extends) {
            return "extends $this->extends ";
        }

        return '';
    }

    public function getConsts(): array
    {
        return $this->consts;
    }

    public function addConst(string $name, $value): PHPClass
    {
        $this->consts[$name] = $value;
        return $this;
    }

    public function addProperty(string $name, string $modifier = 'public', string $defaultValue = ''): PropertyInterface
    {
        return $this->props[] = self::Property($name, $modifier, $defaultValue);
    }


    /**
     * Inner class for properties
     *
     * @param string $name
     * @param string $modifier
     * @param string $defaultValue
     * @return PropertyInterface
     */
    private static function Property(string $name, string $modifier = 'public', string $defaultValue = '')
    {
        return new class($name, $modifier, $defaultValue) extends AbstractGenerator implements PropertyInterface {
            private string  $name;
            private string  $modifier;
            private string  $defaulValue;
            private bool    $isStatic;

            public function __construct(string $name, string $modifier, string $defaulValue = '', bool $isStatic = false)
            {
                $this->name = $name;
                $this->modifier = $modifier ?? 'public';
                $this->defaulValue = $defaulValue;
                $this->isStatic = $isStatic;
            }

            public function generate(): string
            {
                return "$this->modifier $$this->name{$this->generateDefaultValue()};";
            }

            private function generateDefaultValue(): string
            {
                return $this->defaulValue ? " = $this->defaulValue" : '';
            }

            public function getName(): string
            {
                return $this->name;
            }

            public function setName(string $name): void
            {
                $this->name = $name;
            }

            public function getModifier(): string
            {
                return $this->modifier;
            }

            public function setModifier(string $modifier): void
            {
                $this->modifier = $modifier;
            }

            public function getDefaulValue(): string
            {
                return $this->defaulValue;
            }

            public function setDefaulValue(string $defaulValue): void
            {
                $this->defaulValue = $defaulValue;
            }

            public function isStatic(): bool
            {
                return $this->isStatic;
            }

            public function setIsStatic(bool $isStatic): void
            {
                $this->isStatic = $isStatic;
            }
        };
    }
}