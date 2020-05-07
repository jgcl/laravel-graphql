<?php

namespace App\Repositories;

use App\Movement;

class MovementRepository
{
    public function createMovement(Movement $requestMovement): Movement
    {
        $lastMovement = $this->getLastMovement($requestMovement->account);

        $movement = new Movement();
        $movement->account = $requestMovement->account;
        $movement->amount = $requestMovement->amount;
        $movement->balance = ($lastMovement->balance ?? 0) + $movement->amount;
        $movement->save();
        return $movement;
    }

    public function getLastMovement(int $account): ? Movement
    {
        return Movement::query()
            ->where('account', '=', $account)
            ->orderBy('id', 'desc')
            ->first();
    }
}
