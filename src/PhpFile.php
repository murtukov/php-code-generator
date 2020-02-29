<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use Murtukov\PHPCodeGenerator\Structures\PhpClass;
use Murtukov\PHPCodeGenerator\Traits\DependencyAwareTrait;
use Murtukov\PHPCodeGenerator\Traits\IndentableTrait;
use function array_replace;

class PhpFile implements DependencyAwareInterface, GeneratorInterface
{
    use DependencyAwareTrait;
    use IndentableTrait;

    /** @var PhpClass[]  */
    private array $classes;

    /** @var string[] */
    private array $declares;

    protected string $namespace = '';

    private string $name;


    public function __construct(string $name)
    {
        $this->name = $name;
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

    public function getUsePaths(bool $recursive = true): array
    {
        if ($recursive) {
            foreach ($this->classes as $class) {
                $this->usePaths = array_replace($this->usePaths, $class->usePaths);
            }
        }

        return $this->usePaths;
    }

    public function addClass(PhpClass $class): self
    {
        $this->classes[] = $class;

        return $this;
    }

    public function createClass(string $name): PhpClass
    {
        return new PhpClass($name);
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
        # Aggregate use paths from all dependency aware child components
        $usePaths = [];

        # From methods
        /*        foreach ($this->methods as $method) {
                    $usePaths = array_replace($usePaths, $method->getUsePaths());
                }

                # From properties
                foreach ($this->props as $prop) {
                    $usePaths = array_replace($usePaths, $prop->getUsePaths());
                }*/

        # From itself

        $code = '';

        if (!empty($this->usePaths)) {
            $code = "\n";
            foreach ($this->usePaths as $stm => $as) {
                $code .= "use $stm";

                if ($as) {
                    $code .= " as $as";
                }

                $code .= ";\n";
            }
        }

        return $code;
    }
}