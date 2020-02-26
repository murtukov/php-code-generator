<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Murtukov\PHPCodeGenerator\Arrays\AssocArray;
use Murtukov\PHPCodeGenerator\Arrays\NumericArray;
use Murtukov\PHPCodeGenerator\Functions\ArrowFunction;
use Murtukov\PHPCodeGenerator\Structures\PhpClass;
use Murtukov\PHPCodeGenerator\Functions\Method;

$class = new PhpClass("Mutation");
$class->setIsFinal(true);

$class->setNamespace("Overblog\GraphQLBundle\__DEFINITIONS__");
$class->addUseStatement("Overblog\GraphQLBundle\Definition\ConfigProcessor");
$class->addUseStatement("Overblog\GraphQLBundle\Definition\GlobalVariables");
$class->addUseStatement("Symfony\Component\Validator\Constraints", "Assert");
$class->addUseStatement("GraphQL\Type\Definition\Type");

$resolverClosure = ArrowFunction::create('int', '11 + 22');
$resolverClosure->createArgument('name', 'string', 'test');
$resolverClosure->createArgument('age', AssocArray::class, 'new \DateTime()');

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

$class->createProperty('NAME', 'public')->setIsConst(true)->setDefaulValue('Mutation', true);

$class->addMethod($cascadeMethod);
$class->createMethod('__toString')->setReturnType('string');

$class->addImplements("Overblog\GraphQLBundle\Definition\Type\GeneratedTypeInterface");
$class->setExtends("GraphQL\Type\Definition\ObjectType");

//$string = \Murtukov\PHPCodeGenerator\Traits\DependencyAwareTrait::class;

// echo DateTime::class;

echo $class->generate();


// ================================================================================
//$configs = [
//    'Character' => [
//        'type' => 'interface',
//        'config' => [
//            'description' => new DateTime(),
//            'fields' => [
//                'id' => ['type' => 'String!', 'description' => 'The id of the character.'],
//                'name' => ['type' => 'String', 'description' => 'The name of the character.'],
//                'friends' => ['type' => '[Character]', 'description' => 'The friends of the character.'],
//                'appearsIn' => ['type' => '[Episode]', 'description' => 'Which movies they appear in.'],
//            ],
//            'resolveType' => 'Overblog\\GraphQLGenerator\\Tests\\Resolver::resolveType',
//        ],
//    ],
//    /*...*/
//    'Query' => [
//        'type' => 'object',
//        'config' => [
//            'description' => 'A humanoid creature in the Star Wars universe or a faction in the Star Wars saga.',
//            'fields' => [
//                'hero' => [
//                    'type' => 'Character',
//                    'args' => [
//                        'episode' => [
//                            'type' => 'Episode',
//                            'description' => 'If omitted, returns the hero of the whole saga. If provided, returns the hero of that particular episode.',
//                        ],
//                    ],
//                    'resolve' => ['Overblog\\GraphQLGenerator\\Tests\\Resolver', 'getHero'],
//                ],
//            ],
//        ],
//        /*...*/
//    ],
//];
//
//$map = new GeneratorMap($configs, [
//    '*' => function($name, $val) {
//        $class = PhpClass::create($name.'Type')->setExtends('ObjectType')->addImplements('GeneratedTypeInterface');
//
//        $class->createProperty('NAME', 'public', $name);
//        $constructor = $class->createConstructor('public', '');
//
//
//
//        $constructor->appendVar('configLoader', new \Murtukov\PHPCodeGenerator\Functions\Closure());
//    }
//]);

