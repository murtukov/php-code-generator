<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Traits;

use Murtukov\PHPCodeGenerator\ArgumentInterface;
use Murtukov\PHPCodeGenerator\GeneratorInterface;

trait FunctionTrait
{
    use DependencyAwareTrait;

    protected string  $returnType = '';
    protected array   $args = [];


    protected function generateArgs(): string
    {
        return \implode(", ", $this->args);
    }

    public function getReturnType(): string
    {
        return $this->returnType;
    }

    public function setReturnType(string $returnType): self
    {
        $this->returnType = $returnType;
        return $this;
    }

    public function getArguments(): array
    {
        return $this->args;
    }

    public function removeArgumentAt(int $index): self
    {
        unset($this->args[$index]);
        return $this;
    }

    public function createArgument(string $name, string $type = '', $defaultValue = ''): ArgumentInterface
    {

        return $this->args[] = self::newArgument($name, $type, $defaultValue);
    }

    private static function newArgument(string $name, string $type = '', $defaultValue = ''): ArgumentInterface
    {
        return new class($name, $type, $defaultValue) implements ArgumentInterface, GeneratorInterface
        {
            private string  $type;
            private string  $name;
            private bool    $isSpread = false;
            private bool    $isByReference = false;
            private $defaultValue;

            public function __construct(string $name, string $type = '', $defaultValue = '')
            {
                $this->name = $name;
                $this->type = $type;

                $this->setDefaultValue($defaultValue);
            }

            public function generate(): string
            {
                $code = '';

                if ($this->type) {
                    $code .= $this->type . ' ';
                }
                if ($this->isByReference) {
                    $code .= '&';
                }
                if ($this->isSpread) {
                    $code .= '...';
                }

                $code .= '$' . $this->name;

                if ($this->defaultValue) {
                    $code .= " = $this->defaultValue";
                }

                return $code;
            }

            public function __toString(): string
            {
                return $this->generate();
            }

            public function isSpread(): bool
            {
                return $this->isSpread;
            }

            public function setIsSpread(bool $isSpread): ArgumentInterface
            {
                $this->isSpread = $isSpread;
                return $this;
            }

            public function isByReference(): bool
            {
                return $this->isByReference;
            }

            public function setIsByReference(bool $isByReference): ArgumentInterface
            {
                $this->isByReference = $isByReference;
                return $this;
            }

            public function setType(string $type): self
            {
                $this->type = $type;
                return $this;
            }

            public function setDefaultValue($value): self
            {
                if ('string' === $this->type) {
                    $this->defaultValue = "'$value'";
                } else {
                    $this->defaultValue = $value;
                }

                return $this;
            }
        };
    }
}