<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Collection;
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
     * @depends emptyAssoc
     */
    public function addItemsAssoc(Collection $collection)
    {
        $collection
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
}
