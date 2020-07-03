<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use function implode;

class Signature extends DependencyAwareGenerator
{
    use DocBlockTrait;

    public string $name;
    public bool   $isStatic = false;
    public string $modifier;

    protected string $returnType = '';
    protected array  $args = [];
    protected array  $uses = []; // variables of parent scope
    protected string $qualifier;

    public function __construct(
        string $name = '',
        string $modifier = Modifier::NONE,
        string $returnType = '',
        string $qualifier = 'function '
    ) {
        $this->name = $name;
        $this->modifier = $modifier;
        $this->returnType = $this->resolveQualifier($returnType);
        $this->qualifier = $qualifier;

        $this->dependencyAwareChildren = [&$this->args];
    }

    public function getReturnType(): string
    {
        return $this->returnType;
    }

    public function setReturnType(string $returnType): self
    {
        $this->returnType = $this->resolveQualifier($returnType);

        return $this;
    }

    /**
     * Some arguments are stored as simple strings for better performance.
     * If they are requested, they are first converted into objects then
     * returned back.
     *
     * @return Argument
     */
    public function getArgument(int $index = 1): ?Argument
    {
        if ($index-- < 1) {
            return null;
        }

        if (isset($this->args[$index])) {
            $arg = $this->args[$index];

            if (is_string($arg)) {
                return $this->args[$index] = new Argument($arg);
            }

            return $arg;
        }

        return null;
    }

    public function removeArgument(int $index): self
    {
        unset($this->args[--$index]);

        return $this;
    }

    public function removeArguments()
    {
        $this->args = [];

        return $this;
    }

    public function createArgument(string $name, string $type = '', $defaultValue = Argument::NO_PARAM): Argument
    {
        return $this->args[] = new Argument($name, $type, $defaultValue);
    }

    public function addArgument(string $name, string $type = '', $defaultValue = Argument::NO_PARAM): self
    {
        if (1 === func_num_args()) {
            $this->args[] = "$$name";
        } else {
            $this->args[] = new Argument($name, $type, $defaultValue);
        }

        return $this;
    }

    public function addArguments(string ...$names)
    {
        foreach ($names as $name) {
            $this->addArgument($name);
        }

        return $this;
    }

    public function add(FunctionMemberInterface $member): self
    {
        if ($member instanceof Argument) {
            $this->args[] = $member;
        }

        return $this;
    }

    public function bindVar(string $name, bool $isByReference = false): self
    {
        $name = ltrim($name, '$');

        $this->uses[] = $isByReference ? "&$$name" : "$$name";

        return $this;
    }

    public function bindVars(string ...$names)
    {
        foreach ($names as $name) {
            $this->bindVar($name);
        }

        return $this;
    }

    public function generate(bool $withDocBlock = true): string
    {
        $args = join(', ', $this->args);
        $uses = '';
        $isStatic = $this->isStatic ? 'static ' : '';
        $modifier = $this->modifier ? "$this->modifier " : '';
        $returnType = '';

        if (!empty($this->uses)) {
            $uses = ' use ('.implode(', ', $this->uses).')';
        }

        if ('' !== $this->returnType) {
            $returnType = ": $this->returnType";
        }

        $docBlock = '';
        if ($withDocBlock) {
            $docBlock = $this->buildDocBlock();
        }

        return "{$docBlock}{$modifier}{$isStatic}{$this->qualifier}{$this->name}($args){$uses}{$returnType}";
    }

    public function removeBindVars()
    {
        $this->uses = [];
    }
}
