<?php

namespace Murtukov\PHPCodeGenerator\Functions;

class Closure extends AbstractFunction
{
    private array   $uses = []; // variables of parent scope


    public function generate(): string
    {
        $code = <<<CODE
        function ({$this->generateArgs()}){$this->buildUses()}{$this->buildReturnType()} {
        {$this->generateContent()}
        }
        CODE;
        return $code;
    }

    private function buildUses(): string
    {
        if (count($this->uses) > 0) {
            $last = array_key_last($this->uses);

            $code = '';
            foreach ($this->uses as $key => $var) {
                $code .= "$$var";

                if ($key !== $last) {
                    $code .= ', ';
                }
            }

            return " use ($code)";
        }

        return '';
    }

    private function buildReturnType()
    {
        return $this->returnType ? ": $this->returnType" : '';
    }
}