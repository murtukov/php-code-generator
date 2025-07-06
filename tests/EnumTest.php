<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Comment;
use Murtukov\PHPCodeGenerator\EnumType;
use Murtukov\PHPCodeGenerator\IfElse;
use Murtukov\PHPCodeGenerator\Loop;
use Murtukov\PHPCodeGenerator\Method;
use Murtukov\PHPCodeGenerator\Modifier;
use Murtukov\PHPCodeGenerator\Enum;
use PHPUnit\Framework\TestCase;

class EnumTest extends TestCase
{
    /**
     * @test
     */
    public function emptyEnum(): Enum
    {
        $code = <<<CODE
        enum Status
        {
        }
        CODE;

        $enum = Enum::new('Status');
        $this->assertEquals($code, $enum->generate());

        return $enum;
    }

    /**
     * @test
     *
     * @depends emptyEnum
     */
    public function addCases(Enum $enum): Enum
    {
        $code = <<<CODE
        enum Status
        {
            case PENDING;
            case ACTIVE;
            case INACTIVE;
        }
        CODE;

        $enum->addCase('PENDING');
        $enum->addCase('ACTIVE');
        $enum->addCase('INACTIVE');
        
        $this->assertEquals($code, $enum->generate());

        return $enum;
    }

    /**
     * @test
     *
     * @depends addCases
     */
    public function setType(Enum $enum): Enum
    {
        $code = <<<CODE
        enum Status: string
        {
            case PENDING;
            case ACTIVE;
            case INACTIVE;
        }
        CODE;

        $enum->setType(EnumType::STRING);
        
        $this->assertEquals($code, $enum->generate());

        return $enum;
    }

    /**
     * @test
     *
     * @depends setType
     */
    public function addCasesWithValues(Enum $enum): Enum
    {
        $code = <<<CODE
        enum Status: string
        {
            case PENDING = 'pending';
            case ACTIVE = 'active';
            case INACTIVE = 'inactive';
        }
        CODE;

        // Clear existing cases
        $enum = Enum::new('Status')->setType(EnumType::STRING);
        $enum->addCase('PENDING', 'pending');
        $enum->addCase('ACTIVE', 'active');
        $enum->addCase('INACTIVE', 'inactive');
        
        $this->assertEquals($code, $enum->generate());

        return $enum;
    }

    /**
     * @test
     *
     * @depends addCasesWithValues
     */
    public function addImplements(Enum $enum): Enum
    {
        $code = <<<CODE
        enum Status: string implements JsonSerializable
        {
            case PENDING = 'pending';
            case ACTIVE = 'active';
            case INACTIVE = 'inactive';
        }
        CODE;

        $enum->addImplements(JsonSerializable::class);
        
        $this->assertEquals($code, $enum->generate());

        return $enum;
    }

    /**
     * @test
     *
     * @depends addImplements
     */
    public function addMethods(Enum $enum): Enum
    {
        $code = <<<'CODE'
        enum Status: string implements JsonSerializable
        {
            case PENDING = 'pending';
            case ACTIVE = 'active';
            case INACTIVE = 'inactive';

            public function jsonSerialize(): mixed
            {
                return $this->value;
            }
            
            public static function fromValue(string $value): ?self
            {
                foreach (self::cases() as $case) {
                    if ($case->value === $value) {
                        return $case;
                    }
                }
                return null;
            }
        }
        CODE;

        $enum->createMethod('jsonSerialize', Modifier::PUBLIC, 'mixed')
            ->append('return $this->value');
            
        $enum->createMethod('fromValue', Modifier::PUBLIC, '?self')
            ->setStatic()
            ->addArgument('value', 'string')
            ->append(
                Loop::foreach('self::cases() as $case')
                    ->append(
                        IfElse::new('$case->value === $value')
                            ->append('return $case')
                    )
            )
            ->append('return null');
        
        $this->assertEquals($code, $enum->generate());

        return $enum;
    }

    /**
     * @test
     *
     * @depends addMethods
     */
    public function addDocBlock(Enum $enum): Enum
    {
        $code = <<<'CODE'
        /**
         * Status enum representing different states of an entity.
         */
        enum Status: string implements JsonSerializable
        {
            case PENDING = 'pending';
            case ACTIVE = 'active';
            case INACTIVE = 'inactive';

            public function jsonSerialize(): mixed
            {
                return $this->value;
            }
            
            public static function fromValue(string $value): ?self
            {
                foreach (self::cases() as $case) {
                    if ($case->value === $value) {
                        return $case;
                    }
                }
                return null;
            }
        }
        CODE;

        $enum->setDocBlock('Status enum representing different states of an entity.');
        
        $this->assertEquals($code, $enum->generate());

        return $enum;
    }

    /**
     * @test
     *
     * @depends addDocBlock
     */
    public function removeImplements(Enum $enum): Enum
    {
        $code = <<<'CODE'
        /**
         * Status enum representing different states of an entity.
         */
        enum Status: string
        {
            case PENDING = 'pending';
            case ACTIVE = 'active';
            case INACTIVE = 'inactive';

            public function jsonSerialize(): mixed
            {
                return $this->value;
            }
            
            public static function fromValue(string $value): ?self
            {
                foreach (self::cases() as $case) {
                    if ($case->value === $value) {
                        return $case;
                    }
                }
                return null;
            }
        }
        CODE;

        $enum->removeImplements();
        
        $this->assertEquals($code, $enum->generate());

        return $enum;
    }

    /**
     * @test
     * @throws Exception
     */
    public function intEnum(): void
    {
        $code = <<<CODE
        enum HttpStatus: int
        {
            case OK = 200;
            case NOT_FOUND = 404;
            case INTERNAL_SERVER_ERROR = 500;
        }
        CODE;

        $enum = Enum::new('HttpStatus')
            ->setType(EnumType::INT)
            ->addCase('OK', 200)
            ->addCase('NOT_FOUND', 404)
            ->addCase('INTERNAL_SERVER_ERROR', 500);
        
        $this->assertEquals($code, $enum->generate());
    }
}