<?php

namespace App\GraphQL\Queries;

use App\Services\MovementService;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class BalanceQuery extends Query
{
    protected $attributes = [
        'name' => 'Get Balance',
        'description' => 'Get Balance Query'
    ];

    private $movementService;

    public function __construct()
    {
        $this->movementService = new MovementService();
    }

    public function type(): Type
    {
        return Type::float();
    }

    public function args(): array
    {
        return [
            'conta' => [
                'type' => Type::int(),
                'name' => 'conta',
                'rules' => ['min:1']
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $movement = $this->movementService->balance($args['conta']);
        return $movement->balance;
    }
}
