<?php

namespace Murtukov\PHPCodeGenerator;

class Map extends AbstractGenerator
{
    private array $values;
    private $map;
    private $type = 'numeric';

    /**
     * Collection constructor.
     * @param array $values
     * @param callable|array $map
     * @param string $type
     */
    public function __construct(array $values, $map, $type = 'numeric')
    {
        $this->values = $values;
        $this->map = $map;
        $this->type = $type;
    }

    public static function createNumeric(array $values, $map)
    {
        return new static($values, $map, 'numeric');
    }

    public static function createAssoc(array $values, $map)
    {
        return new static($values, $map, 'assoc');
    }

    public function process(): array
    {
        $result = [];

        foreach ($this->values as $key => $value) {
            $result[$key] = ($this->map)($key, $value);
        }

        return $result;
    }

    public function generate(): string
    {
        return '';
    }
}