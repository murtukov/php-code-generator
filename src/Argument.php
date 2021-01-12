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
    private string $name;
    private bool   $isSpread = false;
    private bool   $isByReference = false;
    private bool   $isNullable = false;

    /**
     * @var mixed
     */
    private $defaultValue;

    /**
     * Argument constructor.
     *
     * @param mixed $defaultValue
     *
     * @throws Exception\UnrecognizedValueTypeException
     */
    public final function __construct(string $name, string $type = '', $defaultValue = self::NO_PARAM)
    {
        $this->name = $name;
        $this->type = $this->resolveQualifier($type);

        if (INF !== $defaultValue) {
            $this->defaultValue = Utils::stringify($defaultValue);
        }
    }

    /**
     * @param mixed $defaultValue
     *
     * @return static
     * @throws Exception\UnrecognizedValueTypeException
     */
    public static function new(string $name, string $type = '', $defaultValue = self::NO_PARAM): self
    {
        return new static($name, $type, $defaultValue);
    }

    public function generate(): string
    {
        $code = '';

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
     * @param mixed $value
     *
     * @return $this
     *
     * @throws Exception\UnrecognizedValueTypeException
     */
    public function setDefaultValue($value): self
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
}
