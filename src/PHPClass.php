<?php

namespace Murtukov\PHPCodeGenerator;

use function count;
use function implode;
use function str_replace;

class PHPClass implements GeneratorInterface
{
    private $indent = 4;

    /**
     * @var Property[]
     */
    private $properties;

    /**
     * @var Method[]
     */
    private $methods = [];
    private $namespace = null;
    private $useStatements = [];
    private $name;
    private $extends = null;
    private $implements = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function generate(): string
    {
        return <<<CODE
        <?php declare(strict_types=1);
        
        class $this->name {$this->generateImplements()}
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


    public function addImplements(string $className): self
    {
        $this->implements[] = $className;

        return $this;
    }


    public function setExtends(string $className): self
    {
        $this->extends = $className;

        return $this;
    }


    public function addUseStatement(string $className): self
    {
        $this->useStatements[] = $className;

        return $this;
    }


    public function addProperty(Property $property): self
    {
        $this->properties[] = $property;

        return $this;
    }

    private function generateContent(): string
    {
        $code  = implode("\n", $this->properties);
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
}