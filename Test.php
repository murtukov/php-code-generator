<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Murtukov\PHPCodeGenerator\Arrays\AssocArray;
use Murtukov\PHPCodeGenerator\Arrays\NumericArray;
use Murtukov\PHPCodeGenerator\Functions\ArrowFunction;
use Murtukov\PHPCodeGenerator\PhpFile;
use Murtukov\PHPCodeGenerator\Structures\PhpClass;
use Murtukov\PHPCodeGenerator\Functions\Closure;


$class = PhpClass::create("Mutation")
    ->setIsFinal(true)
    ->setExtends('GraphQL\Type\Definition\ObjectType')
    ->addImplement('Overblog\GraphQLBundle\Definition\Type\GeneratedTypeInterface');

$class->createProperty('NAME', 'public')
    ->setIsConst(true)
    ->setDefaulValue('Mutation', true);

$returnArray = AssocArray::create([], true)
    ->addItem('name', 'Mutation')
    ->addItem('description', null)
    ->addItem('fields', ArrowFunction::create()
        ->setExpression(AssocArray::create([], true)
            ->addItem('updateAppState', AssocArray::create([], true)
                ->addItem('type', 'Type::nonNull($globalVariable->get(\'typeResolver\')->resolve(\'AppStateUpdatePayload\'))')
                ->addItem('args', NumericArray::create([], true)
                    ->push(
                        AssocArray::create([], true)
                            ->addItem('name', 'input') // create different methods addItem, addLiterall, addString, addObject
                            ->addItem('type', 'Type::nonNull($globalVariable->get(\'typeResolver\')->resolve(\'AppStateUpdateInput\'))')
                            ->addItem('description', null)
                        )
                    ->push(
                        AssocArray::create([], true)
                            ->addItem('name', 'input') // create different methods addItem, addLiterall, addString, addObject
                            ->addItem('type', "Type::nonNull(\$globalVariable->get('typeResolver')->resolve('AppStateUpdateInput'))")
                            ->addItem('description', null)
                    )
                )
            )
        )
    );

$constructor = $class->createConstructor();

$constructor->createArgument('configProcessor', 'Overblog\GraphQLBundle\Definition\ConfigProcessor');
$constructor->createArgument('globalVariables', 'Overblog\GraphQLBundle\Definition\GlobalVariables', null);
$constructor->appendVar('configLoader', ArrowFunction::create()->setExpression($returnArray));


$class->createMethod('__toString')->setReturnType('string');

$class2 = new PhpClass('Malakos');

$file = PhpFile::create('Mutation')
    ->setNamespace("Overblog\GraphQLBundle\__DEFINITIONS__")
    ->addClass($class)
    ->addClass($class2);

echo $file;


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

