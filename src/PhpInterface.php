<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use Murtukov\PHPCodeGenerator\Functions\Method;
use Murtukov\PHPCodeGenerator\Functions\Signature;
use function join;

class PhpInterface extends OOPStructure
{
    protected array  $extends    = [];
    protected array  $signatures = [];
    protected array  $consts     = [];
    protected string $name;

    protected ?Comment $docBlock = null;

    public function generate(): string
    {
        $code = join("\n", $this->consts);

        if (!empty($this->signatures)) {
            if (!empty($code)) {
                $code .= "\n\n";
            }

            $code .= join(";\n", $this->signatures) . ';';
        }

        $content = Utils::indent($code);

        // Extends
        $extends = '';
        if (!empty($this->extends)) {
            $extends = ' extends ' . join(', ', $this->extends);
        }

        return <<<CODE
        {$this->buildDocBlock()}interface $this->name{$extends}
        {
        {$content}
        }
        CODE;
    }

    public function createSignature(string $name, string $returnType = ''): Signature
    {
        return $this->signatures[] = new Signature($name, Modifier::PUBLIC, $returnType);
    }

    public function addSignature(string $name, string $returnType = ''): self
    {
        $this->signatures[] = new Signature($name, Modifier::PUBLIC, $returnType);

        return $this;
    }

    public function addSignatureFromMethod(Method $method)
    {
         $this->signatures[] = $method->signature;

         return $this;
    }

    /**
     * @param mixed $value
     */
    public function addConst(string $name, $value): self
    {
        $this->consts[] = Property::new($name, Modifier::PUBLIC, '', $value)->setConst();

        return $this;
    }

    public function addExtends(string ...$extends)
    {
        foreach ($extends as $extend) {
            $this->extends[] = $this->resolveQualifier($extend);
        }

        return $this;
    }

    public function emptyLine()
    {

    }
}