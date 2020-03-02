<?php

namespace Overblog\GraphQLBundle\__DEFINITIONS__;

use GraphQL\Type\Definition\ObjectType;
use Overblog\GraphQLBundle\Definition\Type\GeneratedTypeInterface;

final class QueryType extends ObjectType implements GeneratedTypeInterface
{
    public const NAME = Query;

    public function __constructor(ConfigProcessor $configProcessor, GlobalVariables $globalVariables)
    {
        $configLoader = fn(GlobalVariables $globalVariable) => [
            'name' => 'Query',
            'description' => 'A humanoid creature in the Star Wars universe or a faction in the Star Wars saga.',
            'fields' => fn() => [
                'hero' => [
                    'type' => Type::nonNull($globalVariable->get('typeResolver')->resolve('Character')),
                    'args' => [
                        [
                            'name' => 'episode',
                            'type' => Type::nonNull($globalVariable->get('typeResolver')->resolve('Episode')),
                            'description' => 'If omitted, returns the hero of the whole saga. If provided, returns the hero of that particular episode.',
                        ],
                    ],
                    'resolve' => function ($value, $args, $context, ResolveInfo $info) {

                    },
                ],
            ],
        ];
    }
}