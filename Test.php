<?php

require __DIR__ . '/vendor/autoload.php';

use Murtukov\PHPCodeGenerator\Klass;
use Murtukov\PHPCodeGenerator\Method;

$class = new Klass("Mutation");

$class->addMethod(Method::create('cascadeValidation', null, 'string'));

echo $class->generate();