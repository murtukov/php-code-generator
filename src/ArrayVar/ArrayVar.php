<?php

namespace Murtukov\PHPCodeGenerator\ArrayVar;

use Murtukov\PHPCodeGenerator\GeneratorInterface;
use function array_key_last;
use function gettype;
use function is_int;
use function json_encode;

class ArrayVar implements GeneratorInterface
{
    private bool    $oldSyntax = false;
    private bool    $multiline = false;
    private bool    $isAssoc = true;
    private array   $items;
    private int     $indent = 4;

    public function __construct(array $items = [], bool $multiline = false, bool $isAssoc = true)
    {
        $this->items = $items;
        $this->multiline = $multiline;
        $this->isAssoc = $isAssoc;
    }

    public static function create(array $items = [], bool $multiline = false, bool $isAssoc = true): self
    {
        return new self($items, $multiline, $isAssoc);
    }

    public function addItem(string $key, string $value): self
    {
        $this->items[$key] = $value;

        return $this;
    }

    public function generate(): string
    {
        if (0 === count($this->items)) {
            return '[]';
        }

        if ($this->isAssoc) {
            return $this->generateAssocArray($this->items);
        }

        return $this->generateNumericArray();
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
        $code = '';
        $last = array_key_last($items);

        if ($this->multiline) {
            foreach ($items as $key => $value) {
                $code .= "{$this->stringifyKey($key)} => {$this->stringifyValue($value)},";

                if ($key !== $last) {
                    $code .= "\n";
                }
            }

            return "[\n{$this->indent($code)}\n]";
        } else {
            foreach ($items as $key => $value) {
                $code .= "'$key' => " . $this->stringifyValue($value);

                if ($key !== $last) {
                    $code .= ", ";
                }
            }

            return "[$code]";
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

    private function stringifyKey($key)
    {
        return is_int($key) ? $key : "'$key'";
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


function arrayVar() {

}