<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class Argument extends DependencyAwareGenerator implements FunctionMemberInterface
{
    /**
     * Special value to represent that there is no argument passed.
     */
    public const NO_PARAM = INF;

    private string $type;
    private bool $isSpread = false;
    private bool $isByReference = false;
    private bool $isNullable = false;

    private mixed $defaultValue = '';

    /**
     * Argument constructor.
     */
    final public function __construct(
        private string $name,
        string $type = '',
        mixed $defaultValue = self::NO_PARAM,
        private Modifier $modifier = Modifier::NONE,
    ) {
        $this->type = $this->resolveQualifier($type);

        if (INF !== $defaultValue) {
            $this->defaultValue = Utils::stringify($defaultValue);
        }
    }

    /**
     * @return static
     */
    public static function new(string $name, string $type = '', mixed $defaultValue = self::NO_PARAM, Modifier $modifier = Modifier::NONE): self
    {
        return new static($name, $type, $defaultValue, $modifier);
    }

    public function generate(): string
    {
        $code = '';

        if (Modifier::NONE !== $this->modifier) {
            $code .= $this->modifier->value.' ';
        }

        if ($this->type) {
            if ($this->isNullable && '?' !== $this->type[0]) {
                $code .= '?';
            }
            $code .= $this->type.' ';
        }

        if ($this->isByReference) {
            $code .= '&';
        }

        if ($this->isSpread) {
            $code .= '...';
        }

        $code .= ('$' === $this->name[0]) ? $this->name : "$$this->name";

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

    /**
     * @return $this
     */
    public function setSpread(): self
    {
        $this->isSpread = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function unsetSpread(): self
    {
        $this->isSpread = false;

        return $this;
    }

    public function isByReference(): bool
    {
        return $this->isByReference;
    }

    /**
     * @return $this
     */
    public function setByReference(): self
    {
        $this->isByReference = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function unsetByReference(): self
    {
        $this->isByReference = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return $this
     */
    public function setDefaultValue(mixed $value): self
    {
        if (INF !== $value) {
            $this->defaultValue = Utils::stringify($value);
        } else {
            $this->defaultValue = '';
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function unsetNullable(): self
    {
        $this->isNullable = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function setNullable(): self
    {
        $this->isNullable = true;

        return $this;
    }

    public function setModifier(Modifier $modifier): self
    {
        $this->modifier = $modifier;

        return $this;
    }
}
