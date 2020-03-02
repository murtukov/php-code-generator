<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Arrays;

class AssocArray extends AbstractArray
{
    public function generate(): string
    {
        if ($this->isMap) {
            return $this->generateMap();
        }

        return $this->generateRecursive($this->items);
    }

    public function generateMap(): string
    {
        $result = [];

        foreach ($this->items as $key => $value) {
            $result[$key] = ($this->map)($key, $value);
        }

        return $this->generateRecursive($result);
    }

    public function generateRecursive(array $items): string
    {
        if (empty($this->items)) {
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
                $code .= "{$this->stringifyKey($key)} => {$this->stringifyValue($value)}";

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