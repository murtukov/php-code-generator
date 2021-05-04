<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use function join;

class PhpInterface extends OOPStructure
{
    protected array $extends = [];
    protected ?Comment $docBlock = null;

    public function generate(): string
    {
        // Extends
        $extends = '';
        if (!empty($this->extends)) {
            $extends = ' extends '.join(', ', $this->extends);
        }

        $content = $this->generateWrappedContent("\n", '');

        return <<<CODE
        {$this->buildDocBlock()}interface $this->name{$extends}
        {{$content}
        }
        CODE;
    }

    public function createSignature(string $name, string $returnType = ''): Signature
    {
        $signature = new Signature($name, Modifier::PUBLIC, $returnType);
        $this->append($signature);

        return $signature;
    }

    /**
     * @return $this
     */
    public function addSignature(string $name, string $returnType = ''): self
    {
        return $this->append(new Signature($name, Modifier::PUBLIC, $returnType));
    }

    /**
     * @return $this
     */
    public function addSignatureFromMethod(Method $method): self
    {
        return $this->append($method->signature);
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function addConst(string $name, $value): self
    {
        return $this->append(Property::new($name, Modifier::PUBLIC, '', $value)->setConst());
    }

    /**
     * @return $this
     */
    public function addExtends(string ...$extends): self
    {
        foreach ($extends as $extend) {
            $this->extends[] = $this->resolveQualifier($extend);
        }

        return $this;
    }
}
