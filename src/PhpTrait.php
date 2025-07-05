<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class PhpTrait extends OOPStructure
{
    /**
     * @return $this
     */
    public function addProperty(string $name, Modifier $modifier = Modifier::PUBLIC, string $type = '', mixed $defaulValue = Property::NO_PARAM): self
    {
        return $this->append(new Property($name, $modifier, $type, $defaulValue));
    }

    public function createProperty(string $name, Modifier $modifier = Modifier::PUBLIC, string $type = '', mixed $defaulValue = Property::NO_PARAM): Property
    {
        $property = new Property($name, $modifier, $type, $defaulValue);

        $this->append($property);

        return $property;
    }

    public function addMethod(string $name, Modifier $modifier = Modifier::PUBLIC, string $returnType = ''): self
    {
        return $this->append(new Method($name, $modifier, $returnType))->emptyLine();
    }

    public function createMethod(string $name, Modifier $modifier = Modifier::PUBLIC, string $returnType = ''): Method
    {
        $method = new Method($name, $modifier, $returnType);

        $this->append($method)->emptyLine();

        return $method;
    }

    public function createConstructor(Modifier $modifier = Modifier::PUBLIC): Method
    {
        $constructor = new Method('__construct', $modifier, '');

        $this->append($constructor)->emptyLine();

        return $constructor;
    }

    public function generate(): string
    {
        $content = $this->generateWrappedContent("\n", '');

        return <<<CODE
        {$this->buildDocBlock()}trait $this->name
        {{$content}
        }
        CODE;
    }
}
