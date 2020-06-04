<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Arrays;

use Murtukov\PHPCodeGenerator\Exception\UnrecognizedValueTypeException;
use Murtukov\PHPCodeGenerator\GeneratorInterface;
use Murtukov\PHPCodeGenerator\Utils;

class NumericArray extends AbstractArray
{
    /**
     * @throws UnrecognizedValueTypeException
     */
    public function generate(): string
    {
        return Utils::stringify($this->items, $this->multiline, false, static::getConverters());
    }

    /**
     * @param string|GeneratorInterface $item
     */
    public function push($item): self
    {
        $this->items[] = $item;

        return $this;
    }
}
