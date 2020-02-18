<?php

require __DIR__ . '/vendor/autoload.php';

use Murtukov\PHPCodeGenerator\GeneratorInterface;
use Murtukov\PHPCodeGenerator\PHPClass;
use Murtukov\PHPCodeGenerator\Method;
use Murtukov\PHPCodeGenerator\Property;

$class = new PHPClass("Mutation");

$class->addProperty(Property::create('firstName', 'public'));
$class->addProperty(Property::create('friends', 'public', '[]'));

$class->addMethod(Method::create('cascadeValidation', 'public', 'string'));
$class->addMethod(Method::create('__toString'));

$class->addImplements(GeneratorInterface::class);

echo $class->generate();