<?php

require __DIR__ . '/vendor/autoload.php';

use Murtukov\PHPCodeGenerator\ArrayVar\ArrayVar;
use Murtukov\PHPCodeGenerator\GeneratorInterface;
use Murtukov\PHPCodeGenerator\PHPClass;
use Murtukov\PHPCodeGenerator\Method;
use Murtukov\PHPCodeGenerator\Property;

$class = new PHPClass("Mutation");

$cascadeMethod = Method::create('cascadeValidation', 'public', 'string')
    ->appendVar('numbers', ArrayVar::create([
        'name' => '$Timur',
        'age' => 29,
        'options' => [
            "loko" => "shmoko",
            'rollo' => 'ragnar'
        ],
        'meta' => ['allow_access' => true]
    ], true, false))

    ->appendVar('numbers', ArrayVar::create(["15", "16", "17"]))
    ->appendVar('numbers', ArrayVar::create(["15", "16", "17"]))
;

$class->addProperty(Property::create('firstName', 'public'));
$class->addProperty(Property::create('friends', 'public', '[]'));
$class->addMethod($cascadeMethod);

$class->addMethod(Method::create('__toString'));

$class->addImplements(GeneratorInterface::class);

echo $class->generate();