<?php

namespace Murtukov\PHPCodeGenerator;

use function array_pop;
use function count;
use function explode;
use function implode;
use function in_array;
use function str_replace;

class PHPClass implements GeneratorInterface
{
    /** @var Property[] */
    private array $properties = [];

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

    private int     $indent = 4;
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

    public function addProperty(Property $property): self
    {
        $this->properties[] = $property;

        return $this;
    }

    private function generateContent(): string
    {
        $code .= implode("\n", $this->properties);
        $code .= "\n\n";
        $code .= implode("\n\n", $this->methods);

        return $this->indent($code);
    }

    /**
     * Adds offsets to each line in the code.
     *
     * @param string $code
     * @return string
     */
    private function indent(string $code): string
    {
        $indent = $this->getIndent();

        return $indent . str_replace("\n", "\n$indent", $code);
    }

    private function getIndent(): string
    {
        $indent = '';

        for ($i = 0; $i < $this->indent; ++$i) {
            $indent .= " ";
        }

        return $indent;
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

    /**
     * @return bool
     */
    public function isFinal(): bool
    {
        return $this->isFinal;
    }

    /**
     * @param bool $isFinal
     * @return PHPClass
     */
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
}