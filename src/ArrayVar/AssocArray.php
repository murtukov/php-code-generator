<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\ArrayVar;

class AssocArray extends AbstractArray
{
    public function generate(): string
    {
        return $this->generateRecursive($this->items);
    }

    public function generateRecursive(array $items): string
    {
        if (0 === count($this->items)) {
            return '[]';
        }

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

    private function stringifyKey($key)
    {
        return is_int($key) ? $key : "'$key'";
    }
}