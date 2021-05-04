<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class PhpTrait extends OOPStructure
{
    /**
     * @param mixed $defaulValue
     *
     * @return $this
     */
    public function addProperty(string $name, string $modifier = Modifier::PUBLIC, string $type = '', $defaulValue = Property::NO_PARAM): self
    {
        return $this->append(new Property($name, $modifier, $type, $defaulValue));
    }

    /**
     * @param mixed $defaulValue
     */
    public function createProperty(string $name, string $modifier = Modifier::PUBLIC, string $type = '', $defaulValue = Property::NO_PARAM): Property
    {
        $property = new Property($name, $modifier, $type, $defaulValue);

        $this->append($property);

        return $property;
    }

    public function addMethod(string $name, string $modifier = 'public', string $returnType = ''): self
    {
        return $this->append(new Method($name, $modifier, $returnType))->emptyLine();
    }

    public function createMethod(string $name, string $modifier = 'public', string $returnType = ''): Method
    {
        $method = new Method($name, $modifier, $returnType);

        $this->append($method)->emptyLine();

        return $method;
    }

    public function createConstructor(string $modifier = 'public'): Method
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
