<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use function count;
use function is_callable;

class Collection extends DependencyAwareGenerator
{
    protected array $converters = [];
    protected string $orderBy = 'none';
    protected Utils $utils;

    final private function __construct(
        protected array $items = [],
        protected bool $multiline = false,
        protected bool $withKeys = true,
    ) {
        $this->utils = new Utils();
    }

    /**
     * @return static
     */
    public static function numeric(array $items = [], bool $multiline = false): self
    {
        return new static($items, $multiline, false);
    }

    /**
     * Shorthand for `new AssocArray($items, true)`.
     *
     * @return static
     */
    public static function assoc(array $items = [], bool $multiline = true): self
    {
        return new static($items, $multiline);
    }

    /**
     * Creates a multiline array and adds all provided items after applying a callback to them.
     *
     * @return static
     */
    public static function map(iterable $items, callable $map, bool $withKeys = true): self
    {
        $array = new static([], true, $withKeys);

        foreach ($items as $key => $value) {
            $array->addItem($key, $map($value, $key));
        }

        return $array;
    }

    /**
     * Adds item to the array.
     *
     * @return $this
     */
    public function addItem(string $key, mixed $value): self
    {
        $this->items[$key] = $value;

        if ($value instanceof DependencyAwareGenerator) {
            $this->dependencyAwareChildren[] = $value;
        }

        return $this;
    }

    /**
     * Adds an item to the array if it's not equal null.
     */
    public function addIfNotNull(string $key, mixed $value): self
    {
        if (null === $value) {
            return $this;
        }

        return $this->addItem($key, $value);
    }

    /**
     * Adds item to the array if it's not empty.
     *
     * @return $this
     */
    public function addIfNotEmpty(string $key, mixed $value): self
    {
        if (empty($value)) {
            return $this;
        }

        return $this->addItem($key, $value);
    }

    /**
     * Adds item to the array if it's not equal false.
     *
     * @return $this
     */
    public function addIfNotFalse(string $key, mixed $value): self
    {
        if (false === $value) {
            return $this;
        }

        return $this->addItem($key, $value);
    }

    /**
     * Returns self if value is true or callback returns true, otherwise returns a mock object.
     */
    public function ifTrue(mixed $value): self|Mock
    {
        if (is_callable($value)) {
            $value = $value(); // unwrap the closure
        }

        return $value ? $this : Mock::getInstance($this);
    }

    public function getConverters(): array
    {
        return $this->converters;
    }

    public function addConverter(ConverterInterface $converter): self
    {
        $this->converters[] = $converter;

        return $this;
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

    public function setWithKeys(bool $val = true): self
    {
        $this->withKeys = $val;

        return $this;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getFirstItem(): array|null
    {
        return reset($this->items) ?: null;
    }

    public function generate(): string
    {
        if ('none' !== $this->orderBy) {
            if ('asc' === $this->orderBy) {
                ksort($this->items);
            } elseif ('desc' === $this->orderBy) {
                krsort($this->items);
            }
        }

        return $this->utils->stringify(
            $this->items,
            $this->multiline,
            $this->withKeys,
            $this->getConverters()
        );
    }

    public function push(GeneratorInterface|string $item): self
    {
        $this->items[] = $item;

        if ($item instanceof DependencyAwareGenerator) {
            $this->dependencyAwareChildren[] = $item;
        }

        return $this;
    }

    public function setKeyOrder(string $orderBy): self
    {
        $orderBy = strtolower($orderBy);

        if (in_array($orderBy, ['none', 'asc', 'desc'])) {
            $this->orderBy = $orderBy;
        }

        return $this;
    }
}
