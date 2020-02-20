<?php

require __DIR__ . '/vendor/autoload.php';

use Murtukov\PHPCodeGenerator\Argument;
use Murtukov\PHPCodeGenerator\ArrayVar\ArrayVar;
use Murtukov\PHPCodeGenerator\Closure\ArrowFunction;
use Murtukov\PHPCodeGenerator\PHPClass;
use Murtukov\PHPCodeGenerator\Method;
use Murtukov\PHPCodeGenerator\Property;

$class = new PHPClass("Mutation");
$class->setIsFinal(true);

$class->setNamespace("Overblog\GraphQLBundle\__DEFINITIONS__");
$class->addUseStatement("Overblog\GraphQLBundle\Definition\ConfigProcessor");
$class->addUseStatement("Overblog\GraphQLBundle\Definition\GlobalVariables");
$class->addUseStatement("Symfony\Component\Validator\Constraints", "Assert");
$class->addUseStatement("GraphQL\Type\Definition\Type");


$cascadeMethod = Method::create('cascadeValidation', 'public', 'string')
    ->appendVar('numbers', ArrayVar::create([
        'name' => '$Timur',
        'age' => 29,
        'options' => [
            "loko" => "shmoko",
            'rollo' => 'ragnar'
        ],
        'subarray' => ArrayVar::create([], true, false),
        'resolver' => ArrowFunction::create([], 'int', '11 + 22')
            ->addArgument(Argument::create('name', 'string', 'test'))
            ->addArgument(Argument::create('age', 'int', 'new \DateTime()'))
    ], true))
    ->appendVar('numbers', ArrayVar::create([22, 33, 44]))
;

$class->addProperty(Property::create('firstName', 'public'));
$class->addProperty(Property::create('friends', 'public', '[]'));
$class->addMethod($cascadeMethod);
$class->addMethod(Method::create('__toString')->setReturnType('string'));

$class->addImplements("Overblog\GraphQLBundle\Definition\Type\GeneratedTypeInterface");
$class->setExtends("GraphQL\Type\Definition\ObjectType");

echo $class->generate();