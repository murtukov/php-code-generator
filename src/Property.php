<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class Property extends DependencyAwareGenerator
{
    public string $name;
    public ?Comment $docBlock = null;

    private string $value = '';
    private bool   $isStatic = false;
    private bool   $isConst = false;
    private string $modifier;
    private string $type;

    public function __construct(string $name, ?string $modifier, string $type = '', $defaulValue = '')
    {
        $this->name = $name;
        $this->modifier = $modifier ?? Modifier::PUBLIC;
        $this->value = Utils::stringify($defaulValue);
        $this->type = $this->resolveQualifier($type);
    }

    public static function new(string $name, ?string $modifier, string $type = '', $value = '')
    {
        return new static($name, $modifier, $type, $value);
    }

    public function generate(): string
    {
        $docBlock = $this->docBlock ? "$this->docBlock\n" : '';
        $type     = $this->type     ? "$this->type "      : '';
        $value    = $this->value    ? " = $this->value"   : '';
        $isStatic = $this->isStatic ? "static "           : '';

        if ($this->isConst) {
            return "$docBlock$this->modifier const $this->name$value";
        }

        return "{$docBlock}{$this->modifier} {$isStatic}{$type}$$this->name{$value}";
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getModifier(): string
    {
        return $this->modifier;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue($value): self
    {
        $this->value = Utils::stringify($value);

        return $this;
    }

    public function isStatic(): bool
    {
        return $this->isStatic;
    }

    public function setStatic(): self
    {
        $this->isStatic = true;
        $this->isConst = false;

        return $this;
    }

    public function setConst(): self
    {
        $this->isConst = true;
        $this->isStatic = false;

        return $this;
    }

    public function setPublic(): self
    {
        $this->modifier = 'public';

        return $this;
    }

    public function setPrivate(): self
    {
        $this->modifier = 'private';

        return $this;
    }

    public function setProtected(): self
    {
        $this->modifier = 'protected';

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $this->resolveQualifier($type);

        return $this;
    }
}
