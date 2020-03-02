<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\Functions;

use Murtukov\PHPCodeGenerator\DependencyAwareInterface;
use Murtukov\PHPCodeGenerator\GeneratorInterface;
use Murtukov\PHPCodeGenerator\Traits\FunctionTrait;
use Murtukov\PHPCodeGenerator\Traits\IndentableTrait;
use Murtukov\PHPCodeGenerator\Traits\ScopedContentTrait;
use function implode;

class Method implements DependencyAwareInterface, GeneratorInterface
{
    use IndentableTrait;
    use ScopedContentTrait;
    use FunctionTrait;

    const PUBLIC = 'public';
    const PROTECTED = 'protected';
    const PRIVATE = 'private';

    private string  $name;
    private string  $modifier;
    private array   $customStack = [];

    public static function create(string $name, string $modifier = 'public', string $returnType = ''): self
    {
        return new self($name, $modifier, $returnType);
    }

    public function __construct(string $name, string $modifier = 'public', string $returnType = '')
    {
        $this->name = $name;
        $this->modifier = $modifier;
        $this->returnType = $returnType;
    }

    public function generate(): string
    {
        $signature = "$this->modifier function $this->name({$this->generateArgs()})";

        if ($this->returnType) {
            $signature .= ": $this->returnType";
        }

        return <<<CODE
        $signature
        {
        {$this->generateContent()}
        }
        CODE;
    }

    private function buildReturnType(): string
    {
        return $this->returnType ? ": $this->returnType" : '';
    }

    public function __toString(): string
    {
        return $this->generate();
    }

    public function getReturnType(): string
    {
        return $this->returnType;
    }

    public function setReturnType(string $returnType): Method
    {
        $this->returnType = $returnType;
        return $this;
    }

    public function shortenQulifiers(bool $value): self
    {
        $this->shortenQualifiers = $value;

        return $this;
    }
}