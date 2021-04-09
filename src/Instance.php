<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

class Instance extends DependencyAwareGenerator
{
    private array  $args;
    private string $qualifier;
    public bool   $multiline = false;

    /**
     * @param mixed ...$args
     */
    final public function __construct(string $qualifier, ...$args)
    {
        $this->qualifier = $this->resolveQualifier($qualifier);
        $this->args = $args;
    }

    /**
     * @throws Exception\UnrecognizedValueTypeException
     */
    public function generate(): string
    {
        if (empty($this->args)) {
            return "new $this->qualifier()";
        }

        if ($this->multiline) {
            $args = "\n";
            $suffix = ",\n";
        } else {
            $args = '';
            $suffix = ', ';
        }

        foreach ($this->args as $arg) {
            $args .= Utils::stringify($arg).$suffix;
        }

        if ($this->multiline) {
            $args = Utils::indent($args, false);
        }

        $args = rtrim($args, ', ');

        return "new $this->qualifier($args)";
    }

    /**
     * @param mixed $arg
     *
     * @return $this
     */
    public function addArgument($arg): self
    {
        $this->args[] = $arg;

        return $this;
    }

    /**
     * @param mixed ...$args
     *
     * @return static
     */
    public static function multiline(string $qualifier, ...$args): self
    {
        $instance = new static($qualifier, ...$args);
        $instance->multiline = true;

        return $instance;
    }

    /**
     * @param mixed ...$args
     *
     * @return static
     */
    public static function new(string $qualifier, ...$args): self
    {
        return new static($qualifier, ...$args);
    }

    /**
     * @return $this
     */
    public function setMultiline(): self
    {
        $this->multiline = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function setInline(): self
    {
        $this->multiline = false;

        return $this;
    }
}
