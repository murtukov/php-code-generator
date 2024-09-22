<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator;

use function dirname;
use function file_put_contents;
use function implode;
use function ksort;
use function mkdir;

class PhpFile extends DependencyAwareGenerator
{
    protected string $namespace = '';
    protected string $name;
    protected ?Comment $comment;

    /** @var OOPStructure[] */
    protected array $oopStructures = [];

    /** @var string[] */
    protected array $declares = [];

    public function __construct(string $name = '')
    {
        $this->name = $name;
        $this->dependencyAwareChildren = [&$this->oopStructures];
    }

    public static function new(string $name = ''): self
    {
        return new self($name);
    }

    public function generate(): string
    {
        $namespace = $this->namespace ? "\nnamespace $this->namespace;\n" : '';
        $oopStructures = implode("\n\n", $this->oopStructures);

        return <<<CODE
        <?php
        $namespace{$this->buildUseStatements()}
        $oopStructures
        CODE;
    }

    public function __toString(): string
    {
        return $this->generate();
    }

    public function removeOOPStructure(string $name, ?string $type = null): self
    {
        foreach ($this->oopStructures as $key => $oopStructure) {
            if ($oopStructure->name === $name && ($type === null || $oopStructure instanceof $type)) {
                unset($this->oopStructures[$key]);
            }
        }

        return $this;
    }

    public function getOOPStructure(string $name, ?string $type = null): ?OOPStructure
    {
        foreach ($this->oopStructures as $key => $oopStructure) {
            if ($oopStructure->name === $name && ($type === null || $oopStructure instanceof $type)) {
                return $this->oopStructures[$key];
            }
        }

        return null;
    }

    public function addClass(PhpClass $class): self
    {
        $this->oopStructures[] = $class;

        return $this;
    }

    public function createClass(string $name): PhpClass
    {
        return $this->oopStructures[] = PhpClass::new($name);
    }

    public function removeClass(string $name): self
    {
        return $this->removeOOPStructure($name, PhpClass::class);
    }

    public function getClass(string $name): ?PhpClass
    {
        return $this->getOOPStructure($name, PhpClass::class);
    }

    public function addTrait(PhpTrait $trait): self
    {
        $this->oopStructures[] = $trait;

        return $this;
    }

    public function createTrait(string $name): PhpTrait
    {
        return $this->oopStructures[] = PhpTrait::new($name);
    }

    public function removeTrait(string $name): self
    {
        return $this->removeOOPStructure($name, PhpTrait::class);
    }

    public function getTrait(string $name): ?PhpTrait
    {
        return $this->getOOPStructure($name, PhpTrait::class);
    }

    public function addInterface(PhpInterface $trait): self
    {
        $this->oopStructures[] = $trait;

        return $this;
    }

    public function createInterface(string $name): PhpInterface
    {
        return $this->oopStructures[] = PhpInterface::new($name);
    }

    public function removeInterface(string $name): self
    {
        return $this->removeOOPStructure($name, PhpInterface::class);
    }

    public function getInterface(string $name): ?PhpInterface
    {
        return $this->getOOPStructure($name, PhpInterface::class);
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    private function buildUseStatements(): string
    {
        $code = '';

        $paths = $this->getUsePaths();

        if (empty($paths)) {
            return $code;
        }

        if (!empty(ksort($paths))) {
            $code = "\n";

            foreach ($paths as $path => $aliases) {
                $code .= "use $path";

                if ($aliases) {
                    $code .= " as $aliases";
                }

                $code .= ";\n";
            }
        }

        return $code;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function createComment(string $text): Comment
    {
        return $this->comment = Comment::block($text);
    }

    public function setComment(string $text): self
    {
        $this->comment = Comment::block($text);

        return $this;
    }

    public function removeComment(): self
    {
        $this->comment = null;

        return $this;
    }

    /**
     * @return false|int
     */
    public function save(string $path, int $mask = 0775)
    {
        $dir = dirname($path);

        if (!is_dir($dir)) {
            mkdir($dir, $mask, true);
        }

        return file_put_contents($path, $this);
    }
}
