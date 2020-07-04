<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class Property extends DependencyAwareGenerator
{
    /**
     * Special value to represent that there is no argument passed.
     */
    public const NO_PARAM = INF;

    public string   $name;
    public ?Comment $docBlock = null;
    public bool     $isNullable = false;
    public bool     $isStatic = false;
    public bool     $isConst = false;

    private string $value = '';
    private string $modifier;
    private string $typeHint;

    public function __construct(string $name, ?string $modifier, string $typeHint = '', $defaultValue = self::NO_PARAM)
    {
        $this->name = $name;
        $this->modifier = $modifier ?? Modifier::PUBLIC;
        $this->typeHint = $this->resolveQualifier($typeHint);

        if (INF !== $defaultValue) {
            $this->value = Utils::stringify($defaultValue);

            if (null === $defaultValue) {
                $this->isNullable = true;
            }
        }
    }

    public static function new(
        string $name,
        ?string $modifier = Modifier::PUBLIC,
        string $typeHint = '',
        $value = self::NO_PARAM
    ) {
        return new static($name, $modifier, $typeHint, $value);
    }

    public function generate(): string
    {
        $docBlock = $this->docBlock ? "$this->docBlock\n" : '';
        $value = $this->value ? " = $this->value" : '';
        $isStatic = $this->isStatic ? 'static ' : '';

        $typeHint = '';
        if ($this->typeHint) {
            if ($this->isNullable) {
                $typeHint = '?';
            }

            $typeHint .= "$this->typeHint ";
        }

        if ($this->isConst) {
            return "$docBlock$this->modifier const $this->name$value";
        }

        return "{$docBlock}{$this->modifier} {$isStatic}{$typeHint}$$this->name{$value}";
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

    public function getDefaultValue(): string
    {
        return $this->value;
    }

    public function setDefaultValue($value): self
    {
        $this->value = Utils::stringify($value);

        return $this;
    }

    public function setStatic(): self
    {
        $this->isStatic = true;
        $this->isConst = false;

        return $this;
    }

    public function unsetStatic(): self
    {
        $this->isStatic = false;

        return $this;
    }

    public function setConst(): self
    {
        $this->isConst = true;
        $this->isStatic = false;

        return $this;
    }

    public function unsetConst()
    {
        $this->isConst = false;

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

    public function getTypeHint(): string
    {
        return $this->typeHint;
    }

    public function setTypeHint(string $typeHint): self
    {
        $this->typeHint = $this->resolveQualifier($typeHint);

        return $this;
    }

    public function setNullable()
    {
        $this->isNullable = true;
    }

    public function unsetNullable()
    {
        $this->isNullable = false;
    }
}
