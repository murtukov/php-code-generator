<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use Exception;
use function join;

class Enum extends OOPStructure
{
    protected EnumType $type = EnumType::NONE;
    protected array $implements = [];
    protected array $cases = [];

    /**
     * @return $this
     */
    public function setType(EnumType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return $this
     */
    public function addImplements(string ...$classNames): self
    {
        foreach ($classNames as $name) {
            $this->implements[] = $this->resolveQualifier($name);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removeImplements(): self
    {
        $this->implements = [];

        return $this;
    }

    protected function buildImplements(): string
    {
        return !empty($this->implements) ? ' implements '.join(', ', $this->implements) : '';
    }

    protected function buildType(): string
    {
        return $this->type !== EnumType::NONE ? ": {$this->type->value}" : '';
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function addCase(string $name, int|string $value = null): self
    {
        $case = "case $name";

        if (null !== $value) {
            $case .= ' = ' . Utils::stringify($value);
        }

        $this->cases[] = $case;

        return $this;
    }

    /**
     * @return $this
     */
    public function addMethod(string $name, Modifier $modifier = Modifier::PUBLIC, string $returnType = ''): self
    {
        return $this
            ->append(new Method($name, $modifier, $returnType))
            ->emptyLine()
        ;
    }

    public function createMethod(string $name, Modifier $modifier = Modifier::PUBLIC, string $returnType = ''): Method
    {
        $method = new Method($name, $modifier, $returnType);

        $this->append($method)->emptyLine();

        return $method;
    }

    public function generate(): string
    {
        // Build cases
        $cases = '';
        if (!empty($this->cases)) {
            $cases = "\n    " . join(";\n    ", $this->cases) . ";";

            // Add a newline after cases only if there's content
            if (!empty($this->content)) {
                $cases .= "\n";
            }
        }

        $content = $this->generateWrappedContent("\n", '');

        return <<<CODE
        {$this->buildDocBlock()}enum $this->name{$this->buildType()}{$this->buildImplements()}
        {{$cases}{$content}
        }
        CODE;
    }
}
