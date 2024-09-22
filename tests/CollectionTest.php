<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Collection;
use Murtukov\PHPCodeGenerator\Config;
use Murtukov\PHPCodeGenerator\ConverterInterface;
use Murtukov\PHPCodeGenerator\Instance;
use Murtukov\PHPCodeGenerator\Text;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    /**
     * @test
     */
    public function emptyAssoc(): Collection
    {
        $collection = Collection::assoc();
        $this->expectOutputString('[]');

        echo $collection;

        return $collection;
    }

    /**
     * @test
     *
     * @depends emptyAssoc
     */
    public function addItemsAssoc(Collection $collection): void
    {
        $collection
            ->push('pushedValue')
            ->push(Instance::new('DateTime'))
            ->addItem('name', 'Frank')
            ->addItem('age', 35)
            ->addItem('height', 177.6)
            ->addItem('alive', true)
            ->addItem('money', null)
            ->addItem('friends', ['Alex', 'Mary', 'Paul'])
            ->addItem('foes', Collection::numeric(['Max', 'Joel', 'Bryan'], true))
            ->addItem('colleаgues', Collection::assoc([
                'Jane' => [
                    'position' => 'designer',
                    'age' => 30,
                ], 'Justin' => [
                    'position' => 'manager',
                    'age' => 25,
                ],
            ]))
            ->addItem('parents', Collection::assoc(['mother' => 'Anjela', 'father' => 'Rodrigo'], false))
        ;

        $this->expectOutputString(<<<CODE
        [
            0 => 'pushedValue',
            1 => new DateTime(),
            'name' => 'Frank',
            'age' => 35,
            'height' => 177.6,
            'alive' => true,
            'money' => null,
            'friends' => ['Alex', 'Mary', 'Paul'],
            'foes' => [
                'Max',
                'Joel',
                'Bryan',
            ],
            'colleаgues' => [
                'Jane' => [
                    'position' => 'designer',
                    'age' => 30,
                ],
                'Justin' => [
                    'position' => 'manager',
                    'age' => 25,
                ],
            ],
            'parents' => ['mother' => 'Anjela', 'father' => 'Rodrigo'],
        ]
        CODE);

        echo $collection;
    }

    /**
     * @test
     */
    public function conditionalAdd(): void
    {
        $collection = Collection::assoc();

        $collection
            ->addIfNotEmpty('array', [])
            ->addIfNotEmpty('names', ['Jack', 'Black'])
            ->addIfNotFalse('amount', false)
            ->addIfNotFalse('number', 1)
            ->addIfNotNull('type', null)
            ->addIfNotNull('test', 'Test')
            ->ifTrue(fn () => true)
                ->addItem('test2', 'value2')
            ->ifTrue(true)
                ->addItem('test3', 'value3')
            ->ifTrue(fn () => false)
                ->addItem('test4', 'value4')
            ->ifTrue(false)
                ->addItem('test5', 'value5')
            ->ifTrue('test')
                ->addItem('test6', 'value6')
            ->setMultiline()
        ;

        $this->assertEquals(6, $collection->count());
        $this->assertEquals(['Jack', 'Black'], $collection->getFirstItem());

        $this->assertEquals(<<<CODE
        [
            'names' => ['Jack', 'Black'],
            'number' => 1,
            'test' => 'Test',
            'test2' => 'value2',
            'test3' => 'value3',
            'test6' => 'value6',
        ]
        CODE, $collection->generate());

        $collection->setInline();

        $this->assertEquals(
            "['names' => ['Jack', 'Black'], 'number' => 1, 'test' => 'Test', 'test2' => 'value2', 'test3' => 'value3', 'test6' => 'value6']",
            $collection->generate()
        );
    }

    /**
     * @test
     */
    public function mapCollection(): void
    {
        $array = [
            'constraints' => ['NotNull', 'Length', 'Range'],
            'names' => ['Alix', 'Mohammed'],
            'args' => [
                [
                    'name' => 'default',
                    'type' => 'string',
                ],
                [
                    'name' => 'explicit',
                    'type' => 'int',
                ],
            ],
        ];

        $collection = Collection::map($array, function ($val, $key) {
            if ('constraints' === $key) {
                $collection = Collection::numeric()->setMultiline();

                foreach ($val as $name) {
                    $collection->push(Instance::new($name));
                }

                return $collection;
            }

            if ('names' === $key) {
                return $val;
            }

            if ('args' === $key) {
                return Collection::assoc($val)->setWithKeys(false);
            }

            return [];
        });

        $this->expectOutputString(<<<CODE
        [
            'constraints' => [
                new NotNull(),
                new Length(),
                new Range(),
            ],
            'names' => ['Alix', 'Mohammed'],
            'args' => [
                [
                    'name' => 'default',
                    'type' => 'string',
                ],
                [
                    'name' => 'explicit',
                    'type' => 'int',
                ],
            ],
        ]
        CODE);

        echo $collection;
    }

    /**
     * @test
     */
    public function stringifyWithCustomConverter(): void
    {
        $converter = new class implements ConverterInterface {
            public function convert($value)
            {
                return new Text(ltrim($value, 'pre_'));
            }

            public function check($string): bool
            {
                if (\is_string($string) && 'pre_' === substr($string, 0, 4)) {
                    return true;
                }

                return false;
            }
        };

        Config::registerConverter($converter, ConverterInterface::TYPE_STRING);

        $array = [
            'firstName' => 'pre_Timur',
            'lastName' => 'pre_Murtukov',
        ];

        $this->assertEquals(
            <<<CODE
            [
                'firstName' => 'Timur',
                'lastName' => 'Murtukov',
            ]
            CODE,
            Collection::assoc($array)->addConverter($converter)->generate()
        );

        Config::unregisterConverter(get_class($converter));

        $this->assertEquals(
            <<<CODE
            [
                'firstName' => 'pre_Timur',
                'lastName' => 'pre_Murtukov',
            ]
            CODE,
            Collection::assoc($array)->addConverter($converter)->generate()
        );
    }

    /**
     * @test
     */
    public function orderBy(): void
    {
        $collection = Collection::assoc();

        $collection
            ->addItem('name', 'Timur')
            ->addItem('age', 30)
            ->addItem('type', 'human')
            ->addItem('friends', [])
        ;

        $collection->setKeyOrder('asc');

        $this->assertEquals(<<<CODE
        [
            'age' => 30,
            'friends' => [],
            'name' => 'Timur',
            'type' => 'human',
        ]
        CODE, $collection->generate());

        $collection->setKeyOrder('DESC');

        $this->assertEquals(<<<CODE
        [
            'type' => 'human',
            'name' => 'Timur',
            'friends' => [],
            'age' => 30,
        ]
        CODE, $collection->generate());
    }
}
