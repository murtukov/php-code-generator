<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use Murtukov\PHPCodeGenerator\Structures\PhpClass;
use Murtukov\PHPCodeGenerator\Traits\DependencyAwareTrait;
use Murtukov\PHPCodeGenerator\Traits\IndentableTrait;

class PhpFile extends DependencyAwareGenerator
{
    use IndentableTrait;

    /** @var PhpClass[]  */
    private array $classes = [];

    /** @var string[] */
    private array $declares;

    protected string $namespace = '';

    private string $name;


    public function __construct(string $name)
    {
        $this->name = $name;
        $this->dependencyAwareChildren = [&$this->classes];
    }

    public static function create(string $name): self
    {
        return new self($name);
    }

    public function generate(): string
    {
        return <<<FILE
        <?php 
        {$this->buildNamespace()}{$this->buildUseStatements()}
        {$this->buildClasses()}
        FILE;
    }

    public function __toString(): string
    {
        return $this->generate();
    }

    public function addClass(PhpClass $class): self
    {
        $this->classes[] = $class;

        return $this;
    }

    public function createClass(string $name): PhpClass
    {
        return $this->classes[] = new PhpClass($name);
    }

    protected function buildNamespace(): string
    {
        if ($this->namespace) {
            return "\nnamespace $this->namespace;\n";
        }

        return '';
    }

    private function buildClasses(): string
    {
        return implode("\n\n", $this->classes);
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function addUseStatement(string $fqcn, string $alias = ''): self
    {
        $this->usePaths[$fqcn] = $alias;

        return $this;
    }

    public function buildUseStatements(): string
    {
        $code = '';

        $paths = $this->getUsePaths();

        if (!empty(ksort($paths))) {
            $code = "\n";

            foreach ($paths as $path => $alias) {
                $code .= "use $path";

                if ($alias) {
                    $code .= " as $alias";
                }

                $code .= ";\n";
            }
        }

        return $code;
    }
}