<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Traits;

use Murtukov\PHPCodeGenerator\AbstractGenerator;
use Murtukov\PHPCodeGenerator\DependencyAwareGenerator;
use Murtukov\PHPCodeGenerator\Functions\ArrowFunction;
use Murtukov\PHPCodeGenerator\GeneratorInterface;
use function array_unshift;

trait ScopedContentTrait
{
    private array $content = [];

    public function appendVar(string $name, GeneratorInterface $var): self
    {
        $this->content[] = self::createBlock("$$name = ", $var);

        $this->checkIfChildWithDependency($var);

        return $this;
    }

    public function append(GeneratorInterface $object): self
    {
        $this->content[] = $object;

        $this->checkIfChildWithDependency($object);

        return $this;
    }

    public function prepend(GeneratorInterface $object): self
    {
        array_unshift($this->content, $object);

        $this->checkIfChildWithDependency($object);

        return $this;
    }

    public function appendEmptyLine(): self
    {
        $this->content[] = "\n";

        return $this;
    }

    public function appendFn(array $args = [], string $returnType = '', GeneratorInterface $expression = null): self
    {
        $function = new ArrowFunction($expression, $returnType);
        $this->content[] = &$function;

        $this->checkIfChildWithDependency($function);

        return $this;
    }

    public function setReturn(GeneratorInterface $object): self
    {
        $this->content[] = "return $object";

        return $this;
    }

    private function checkIfChildWithDependency($child)
    {
        if ($child instanceof DependencyAwareGenerator) {
            $this->dependencyAwareChildren[] = &$child;
        }
    }

    private function generateContent(): string
    {
        $content = '';

        if (!empty($this->content)) {
            $content = $this->indent(implode(";\n", $this->content).';');
        }

        return $content;
    }

    private static function createBlock(...$parts)
    {
        /**
         * @class Block
         */
        return new class(...$parts) extends AbstractGenerator
        {
            public array $parts;

            public function __construct(...$args)
            {
                $this->parts = $args;
            }

            public function generate(): string
            {
                return implode($this->parts);
            }
        };
    }
}