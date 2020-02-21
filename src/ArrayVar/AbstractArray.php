<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\ArrayVar;

use Murtukov\PHPCodeGenerator\AbstractGenerator;
use Murtukov\PHPCodeGenerator\Traits\IndentableTrait;
use function gettype;
use function json_encode;

abstract class AbstractArray extends AbstractGenerator
{
    use IndentableTrait;

    protected bool  $oldSyntax = false;
    protected bool  $multiline = false;
    protected array $items;

    public function __construct(array $items = [], bool $multiline = false)
    {
        $this->items = $items;
        $this->multiline = $multiline;
    }

    public static function create(array $items = [], bool $multiline = false): self
    {
        return new static($items, $multiline);
    }

    public function addItem(string $key, string $value): self
    {
        $this->items[$key] = $value;

        return $this;
    }

    public function removeItemAt(int $index): self
    {
        unset($this->items[$index]);
    }

    protected function stringifyValue($value)
    {
        switch (gettype($value)) {
            case 'boolean':
            case 'integer':
            case 'double':
                return json_encode($value);
            case 'string':
                return "'$value'";
            case 'array':
                return $this->generateRecursive($value);
            case 'object':
                return $value;
            case 'NULL':
                return "'null'";
            default:
                return "undefined";

        }
    }
}