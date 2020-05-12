<?php

namespace App\Services;

use App\Movement;
use App\Repositories\MovementRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class MovementService
{
    private $movementRepository;

    public function __construct()
    {
        $this->movementRepository = new MovementRepository();
    }

    public function withdraw(int $account, int $amount): Movement
    {
        return Cache::lock("account_{$account}", 10)->block(5, function () use($account, $amount) {
            // Lock acquired after waiting maximum of 5 seconds...
            $lastMovement = $this->movementRepository->getLastMovement($account);
            if (empty($lastMovement)) {
                throw ValidationException::withMessages(['account' => 'Conta não encontrada, faça um depósito antes']);
            }

            if ($amount > $lastMovement->balance) {
                throw ValidationException::withMessages(['balance' => 'Saldo insuficiente']);
            }

            $requestMovement = new Movement();
            $requestMovement->account = $account;
            $requestMovement->amount = $amount * -1;

            return $this->movementRepository->createMovement($requestMovement);
        });
    }

    public function deposit(int $account, int $amount): Movement
    {
        return Cache::lock("account_{$account}", 10)->block(5, function () use($account, $amount) {
            // Lock acquired after waiting maximum of 5 seconds...
            $requestMovement = new Movement();
            $requestMovement->account = $account;
            $requestMovement->amount = $amount;
            return $this->movementRepository->createMovement($requestMovement);
        });
    }

    public function balance(int $account): Movement
    {
        return Cache::lock("account_{$account}", 10)->block(5, function () use($account) {
            // Lock acquired after waiting maximum of 5 seconds...
            $movement = $this->movementRepository->getLastMovement($account);

            if (empty($movement)) {
                throw ValidationException::withMessages(['account' => 'Conta não encontrada.']);
            }

            return $movement;
        });
    }
}
