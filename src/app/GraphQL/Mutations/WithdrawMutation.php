<?php

namespace App\GraphQL\Mutations;

use App\Movement;
use App\Services\MovementService;
use Closure;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class WithdrawMutation extends Mutation
{
    protected $attributes = [
        'name' => 'Sacar',
        'description' => 'Saque simples'
    ];

    private $movementService;

    public function __construct()
    {
        $this->movementService = new MovementService();
    }

    public function type(): Type
    {
        return GraphQL::type('movement');
    }

    public function args(): array
    {
        return [
            'conta' => [
                'type' => Type::nonNull(Type::int()),
                'rules' => ['min:1']
            ],
            'valor' => [
                'type' => Type::nonNull(Type::float()),
                'rules' => ['min:0']
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        return $this->movementService->withdraw($args['conta'], $args['valor']);
    }
}
