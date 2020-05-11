<?php

namespace App\GraphQL\Types;

use App\Movement;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class MovementType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'MovementType',
        'description'   => 'A bank movement',
        'model'         => Movement::class,
    ];

    public function fields(): array
    {
        return [
            /*'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the movement',
            ],*/
            'conta' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Account',
                'alias' => 'account'
            ],
            /*'valor' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Amount of transaction',
                'alias' => 'amount'
            ],*/
            'saldo' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Account balance',
                'alias' => 'balance'
            ],
            /*'data' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Date of transaction',
                'alias' => 'created_at'
            ]*/
        ];
    }
}
