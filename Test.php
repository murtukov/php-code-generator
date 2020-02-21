<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Murtukov\PHPCodeGenerator\Argument;
use Murtukov\PHPCodeGenerator\Arrays\AssocArray;
use Murtukov\PHPCodeGenerator\Arrays\NumericArray;
use Murtukov\PHPCodeGenerator\Functions\ArrowFunction;
use Murtukov\PHPCodeGenerator\PHPClass;
use Murtukov\PHPCodeGenerator\Functions\Method;

$class = new PHPClass("Mutation");
$class->setIsFinal(true);

$class->setNamespace("Overblog\GraphQLBundle\__DEFINITIONS__");
$class->addUseStatement("Overblog\GraphQLBundle\Definition\ConfigProcessor");
$class->addUseStatement("Overblog\GraphQLBundle\Definition\GlobalVariables");
$class->addUseStatement("Symfony\Component\Validator\Constraints", "Assert");
$class->addUseStatement("GraphQL\Type\Definition\Type");

$resolverClosure = ArrowFunction::create([], 'int', '11 + 22')
    ->addArgument(Argument::create('name', 'string', 'test'))
    ->addArgument(Argument::create('age', 'int', 'new \DateTime()'));

$cascadeMethod = Method::create('cascadeValidation', 'public', 'string')
    ->appendVar('numbers', AssocArray::create([
        'name' => '$Timur',
        'age' => 29,
        'options' => [
            "loko" => "shmoko",
            'rollo' => 'ragnar'
        ],
        'subarray' => AssocArray::create([], true),
        'resolver' => $resolverClosure
    ], true))
    ->appendVar('numbers', NumericArray::create([22, 33, 44]))
;

$property = $class->addProperty('firstName', 'public');
$prop = $class->addProperty('friends', 'public', '[]');
$class->addMethod($cascadeMethod);
$class->createMethod('__toString')->setReturnType('string');

$class->addImplements("Overblog\GraphQLBundle\Definition\Type\GeneratedTypeInterface");
$class->setExtends("GraphQL\Type\Definition\ObjectType");

echo $class->generate();
//
//// Function
//function tiesto(string $test): string
//{
//    echo "Hello, World!";
//
//    return "Yes!";
//}
//
//// Method
//public function tiesto2(string $test): string
//{
//    echo "Hello, World!";
//
//    return "Yes!";
//}
//
//// Closure
//function (string $test) use ($class): string  {
//    echo "Hello, World!";
//
//    return "Yes!";
//}
//
//// Arrow function
//fn(string $test) => 11 + 15

