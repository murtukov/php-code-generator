<?php

namespace Murtukov\PHPCodeGenerator;

use function str_replace;

class Klass implements GeneratorInterface
{
    private int     $indent = 4;
    private array   $properties;

    /**
     * @var Method[]
     */
    private array   $methods = [];
    private ?string $namespace = null;
    private array   $useStatements = [];
    private string  $name;
    private ?string $extends = null;
    private array   $implements = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function generate(): string
    {
        return <<<CODE
        <?php declare(strict_types=1);
        
        class $this->name
        {
        {$this->generateContent()}
        }
        CODE;
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
        $code = '';

        foreach ($this->methods as $method) {
            $code .= $method->generate();
        }

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
}