<?php

namespace Murtukov\PHPCodeGenerator\ArrayVar;

use Murtukov\PHPCodeGenerator\GeneratorInterface;
use function gettype;
use function json_encode;

class ArrayVar implements GeneratorInterface
{
    private $oldSyntax = false;
    private $multiline = false;
    private $isNumeric = true;
    private $items;
    private $indent = 4;

    public function __construct(array $items = [], bool $multiline = false, bool $isNumeric = true)
    {
        $this->items = $items;
        $this->multiline = $multiline;
        $this->isNumeric = $isNumeric;
    }

    public static function create(array $items = [], bool $multiline = false, bool $isNumeric = true): self
    {
        return new self($items, $multiline, $isNumeric);
    }

    public function addItem(string $key, string $value): self
    {
        $this->items[$key] = $value;

        return $this;
    }

    public function generate(): string
    {
        if ($this->isNumeric) {
            return $this->generateNumericArray();
        }

        return $this->generateAssocArray($this->items);
    }

    public function generateNumericArray(): string
    {
        if (!$this->multiline) {
            return '['.implode(', ', $this->items).']';
        }

        $indent = $this->getIndent();

        return "[\n$indent".implode("\n$indent", $this->items)."\n]";
    }

    private function generateAssocArray($items): string
    {
        if ($this->multiline) {
            $code = '';
            foreach ($items as $key => $value) {
                $code .= "'$key' => " . $this->stringifyValue($value);

                if ($key !== array_key_last($items)) {
                    $code .= "\n";
                }
            }

            return "[\n{$this->indent($code)}\n]";
        } else {
            $code = "[";
            foreach ($items as $key => $value) {
                $code .= "'$key' => " . $this->stringifyValue($value) . ", ";
            }
        }
    }

    private function stringifyValue($value)
    {
        switch (gettype($value)) {
            case 'boolean':
            case 'integer':
            case 'double':
                return json_encode($value);
            case 'string':
                return "'$value'";
            case 'array':
                return $this->generateAssocArray($value);
            case 'object':
                return $value;
            case 'NULL':
                return "'null'";
            default:
                return "undefined";

        }
    }

    /**
     * Adds offsets to each line in the code.
     *
     * @param string $code
     * @return string
     */
    private function indent(string $code): string
    {
        $indent = $this->getIndent();

        return $indent . str_replace("\n", "\n$indent", $code);
    }

    public function __toString(): string
    {
        return $this->generate();
    }

    private function getIndent(): string
    {
        $indent = '';

        for ($i = 0; $i < $this->indent; ++$i) {
            $indent .= " ";
        }

        return $indent;
    }
}