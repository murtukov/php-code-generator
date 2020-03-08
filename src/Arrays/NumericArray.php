<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Arrays;

use Murtukov\PHPCodeGenerator\GeneratorInterface;

class NumericArray extends AbstractArray
{
    public function generate(): string
    {
        return $this->generateRecursive($this->items);
    }

    public function generateMap(): string
    {
        $result = [];

        foreach ($this->items as $key => $value) {
            $result[] = ($this->map)($key, $value);
        }

        return $this->generateRecursive($result);
    }

    private function generateRecursive(array $items): string
    {
        if (0 === count($this->items)) {
            return '[]';
        }

        $code = '';
        $last = array_key_last($items);

        if ($this->multiline) {
            foreach ($items as $key => $value) {
                $code .= "{$this->stringifyValue($value)},";

                if ($key !== $last) {
                    $code .= "\n";
                }
            }

            return "[\n{$this->indent($code)}\n]";
        } else {
            foreach ($items as $key => $value) {
                $code .= "{$this->stringifyValue($value)}";

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

    /**
     * @param string|GeneratorInterface $item
     * @return $this
     */
    public function push($item): self
    {
        $this->items[] = $item;

        return $this;
    }
}