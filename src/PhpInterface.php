<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use Murtukov\PHPCodeGenerator\Functions\Method;
use Murtukov\PHPCodeGenerator\Functions\Signature;
use function join;

class PhpInterface extends OOPStructure
{
    protected array  $extends = [];
    protected string $name;

    protected ?Comment $docBlock = null;

    public function generate(): string
    {
        // Extends
        $extends = '';
        if (!empty($this->extends)) {
            $extends = ' extends ' . join(', ', $this->extends);
        }

        return <<<CODE
        {$this->buildDocBlock()}interface $this->name{$extends}
        {
        {$this->generateContent()}
        }
        CODE;
    }

    public function createSignature(string $name, string $returnType = ''): Signature
    {
        $signature = new Signature($name, Modifier::PUBLIC, $returnType);
        $this->append($signature);

        return $signature;
    }

    public function addSignature(string $name, string $returnType = ''): self
    {
        return $this->append(new Signature($name, Modifier::PUBLIC, $returnType));
    }

    public function addSignatureFromMethod(Method $method)
    {
         return $this->append($method->signature);
    }

    /**
     * @param mixed $value
     */
    public function addConst(string $name, $value): self
    {
        return $this->append(Property::new($name, Modifier::PUBLIC, '', $value)->setConst());
    }

    public function addExtends(string ...$extends)
    {
        foreach ($extends as $extend) {
            $this->extends[] = $this->resolveQualifier($extend);
        }

        return $this;
    }
}