<?php

declare(strict_types=1);

use Murtukov\PHPCodeGenerator\Collection;
use Murtukov\PHPCodeGenerator\Utils;
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
        $arr = Utils::stringify([
            'name' => 'Frank',
            'age' => 35,
            'height' => 177.6,
            'alive' => true,
            'money' => null,
            'friends' => Collection::numeric(['Alex', 'Mary', 'Paul']),
            'colleagues' => [
                'Jane' => [
                    'position' => 'designer',
                    'age' => 30
                ],
                'Justin' => [
                    'position' => 'manager',
                    'age' => 25
                ],
            ],
        ]);


        $collection
            ->addItem('name', 'Frank')
            ->addItem('age', 35)
            ->addItem('height', 177.6)
            ->addItem('alive', true)
            ->addItem('money', null)
            ->addItem('friends', ['Alex', 'Mary', 'Paul'])
            ->addItem('colleаgues', Collection::assoc(['Jane' => Collection::assoc([
                    'position' => 'designer',
                    'age' => 30
                ])->setInline(), 'Justin' => [
                    'position' => 'manager',
                    'age' => 25
                ],
            ]))->generate()
        ;

        $this->expectOutputString(<<<CODE
        [
            'name' => 'Frank',
            'age' => 35,
            'height' => 177.6,
            'alive' => true,
            'money' => null,
            'friends' => ['Alex', 'Mary', 'Paul'],
            'colleagues' => [
                'Jane' => [
                    'position' => 'designer', 
                    'age' => 30
                ],
                'Justin' => [
                    'position' => 'manager', 
                    'age' => 25
                ],
            ],
        ]
        CODE);

        echo $collection;
    }
}
