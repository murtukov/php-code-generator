<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use function func_num_args;
use function is_string;
use function join;
use function ltrim;

class Signature extends DependencyAwareGenerator
{
    use DocBlockTrait;

    public bool $isStatic = false;
    public bool $isMultiline = false;

    protected string $returnType = '';
    protected array $args = [];
    protected array $uses = []; // variables of parent scope

    public function __construct(
        public string $name = '',
        public string $modifier = Modifier::NONE,
        string $returnType = '',
        protected string $qualifier = 'function ',
    ) {
        $this->returnType = $this->resolveQualifier($returnType);
        $this->dependencyAwareChildren = [&$this->args];
    }

    public function getReturnType(): string
    {
        return $this->returnType;
    }

    /**
     * @return $this
     */
    public function setReturnType(string $returnType): self
    {
        $this->returnType = $this->resolveQualifier($returnType);

        return $this;
    }

    /**
     * Some arguments are stored as simple strings for better performance.
     * If they are requested, they are first converted into objects then
     * returned back.
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

    /**
     * @return $this
     */
    public function removeArguments(): self
    {
        $this->args = [];

        return $this;
    }

    public function createArgument(
        string $name,
        string $type = '',
        mixed $defaultValue = Argument::NO_PARAM,
        string $modifier = Modifier::NONE,
    ): Argument {
        return $this->args[] = new Argument($name, $type, $defaultValue, $modifier);
    }

    /**
     * @return $this
     */
    public function addArgument(
        string $name,
        string $type = '',
        mixed $defaultValue = Argument::NO_PARAM,
        string $modifier = Modifier::NONE,
    ): self {
        if (1 === func_num_args()) {
            $this->args[] = "$$name";
        } else {
            $this->args[] = new Argument($name, $type, $defaultValue, $modifier);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function addArguments(string ...$names): self
    {
        foreach ($names as $name) {
            $this->addArgument($name);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function add(FunctionMemberInterface $member): self
    {
        $this->args[] = $member;

        return $this;
    }

    /**
     * @return $this
     */
    public function bindVar(string $name, bool $isByReference = false): self
    {
        $name = ltrim($name, '$');

        $this->uses[] = $isByReference ? "&$$name" : "$$name";

        return $this;
    }

    /**
     * @return $this
     */
    public function bindVars(string ...$names): self
    {
        foreach ($names as $name) {
            $this->bindVar($name);
        }

        return $this;
    }

    public function generate(bool $withDocBlock = true): string
    {
        if ($this->isMultiline) {
            $args = "\n".Utils::indent(join(",\n", $this->args))."\n";
        } else {
            $args = join(', ', $this->args);
        }

        $uses = '';
        $isStatic = $this->isStatic ? 'static ' : '';
        $modifier = $this->modifier ? "$this->modifier " : '';
        $returnType = '';

        if (!empty($this->uses)) {
            $uses = ' use ('.join(', ', $this->uses).')';
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

    public function removeBindVars(): void
    {
        $this->uses = [];
    }

    public function setMultiline(bool $isMultiline = true): self
    {
        $this->isMultiline = $isMultiline;

        return $this;
    }

    public function unsetMultiline(): self
    {
        $this->isMultiline = false;

        return $this;
    }
}
