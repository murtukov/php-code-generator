<?php

namespace Murtukov\PHPCodeGenerator\Closure;

class Closure extends AbstractClosure
{
    private string  $name;

    public function generate(): string
    {
        $code = "function ({$this->generateArgs()})[: RETURN_TYPE] {\n
            [CONTENT]
        }";

        return $code;
    }

    protected function generateArgs(): string
    {
        return '';
    }
}