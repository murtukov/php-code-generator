<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use Closure;
use function count;
use function is_bool;
use function is_callable;

class Collection extends DependencyAwareGenerator
{
    protected array $items = [];
    protected bool  $multiline = false;
    protected bool  $withKeys = true;

    protected Utils $utils;

    public function __construct(array $items = [], bool $multiline = false, bool $withKeys = true)
    {
        $this->items = $items;
        $this->multiline = $multiline;
        $this->withKeys = $withKeys;
        $this->utils = new Utils();
    }

    public static function numeric(array $items = [], bool $multiline = false): self
    {
        return new static($items, $multiline, false);
    }

    /**
     * Shorthand for `new AssocArray($items, true)`.
     *
     * @return Collection
     */
    public static function assoc(array $items = [], bool $multiline = true): self
    {
        return new static($items, $multiline);
    }

    /**
     * Creates a multiline array and adds all provided items, after applying a callback to them.
     */
    public static function map(array $items, callable $map): self
    {
        $array = new static([], true);

        foreach ($items as $key => $value) {
            $array->addItem($key, $map($value, $key));
        }

        return $array;
    }

    /**
     * Adds item to the array.
     *
     * @param mixed $value
     */
    public function addItem(string $key, $value): self
    {
        $this->items[$key] = $value;

        if ($value instanceof DependencyAwareGenerator) {
            $this->dependencyAwareChildren[] = $value;
        }

        return $this;
    }

    /**
     * Adds item to the array if it's not equal null.
     *
     * @param mixed $value
     */
    public function addIfNotNull(string $key, $value): self
    {
        if (null === $value) {
            return $this;
        }

        return $this->addItem($key, $value);
    }

    /**
     * Adds item to the array if it's not empty.
     *
     * @param mixed $value
     */
    public function addIfNotEmpty(string $key, $value): self
    {
        if (empty($value)) {
            return $this;
        }

        return $this->addItem($key, $value);
    }

    /**
     * Adds item to the array if it's not equal false.
     *
     * @param mixed $value
     */
    public function addIfNotFalse(string $key, $value): self
    {
        if (!$value) {
            return $this;
        }

        return $this->addItem($key, $value);
    }

    /**
     * Returns self if value is not null, otherwise returns a mock object.
     *
     * @param mixed $value
     *
     * @return self|Mock
     */
    public function ifNotNull($value)
    {
        if (null !== $value) {
            return $this;
        }

        return Mock::getInstance($this);
    }

    /**
     * Returns self if value is true or callback returns true, otherwise returns a mock object.
     *
     * @param bool|Closure $value
     *
     * @return self|Mock
     */
    public function ifTrue($value)
    {
        if (is_bool($value)) {
            return $value ? $this : Mock::getInstance($this);
        } elseif (is_callable($value)) {
            return $value() ? $this : Mock::getInstance($this);
        }

        return Mock::getInstance($this);
    }

    /**
     * Returns self if value is not empty, otherwise returns a mock object.
     *
     * @param mixed $value
     *
     * @return self|Mock
     */
    public function ifNotEmpty($value)
    {
        return !empty($value) ? $this : Mock::getInstance($this);
    }

    public static function getConverters()
    {
        return [];
    }

    public function setMultiline(): self
    {
        $this->multiline = true;

        return $this;
    }

    public function setInline(): self
    {
        $this->multiline = false;

        return $this;
    }

    public function count()
    {
        return count($this->items);
    }

    /**
     * @return GeneratorInterface|string|null
     */
    public function getFirstItem()
    {
        return reset($this->items) ?: null;
    }

    public function generate(): string
    {
        return $this->utils->stringify(
            $this->items,
            $this->multiline,
            $this->withKeys,
            static::getConverters()
        );
    }

    /**
     * @param string|GeneratorInterface $item
     */
    public function push($item): self
    {
        $this->items[] = $item;

        if ($item instanceof DependencyAwareGenerator) {
            $this->dependencyAwareChildren[] = $item;
        }

        return $this;
    }
}
