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
        return Cache::lock($this->getAtomicLockKey($account))->block(1, function () use ($account, $amount) {
            $lastMovement = $this->movementRepository->getLastMovement($account);

            if($amount > $lastMovement->balance) {
                throw new \Exception("Saldo insificiente");
            }

            $requestMovement = new Movement();
            $requestMovement->account = $account;
            $requestMovement->amount = $amount * -1;

            return $this->movementRepository->createMovement($requestMovement);
        });
    }

    public function deposit(int $account, int $amount): Movement
    {
        return Cache::lock($this->getAtomicLockKey($account))->block(1, function () use ($account, $amount) {
            $requestMovement = new Movement();
            $requestMovement->account = $account;
            $requestMovement->amount = $amount;
            return $this->movementRepository->createMovement($requestMovement);
        });
    }

    public function balance(int $account): Movement
    {
        return Cache::lock($this->getAtomicLockKey($account))->block(1, function () use ($account) {
            $movement = $this->movementRepository->getLastMovement($account);

            if(empty($movement))
                throw ValidationException::withMessages(['account' => 'Conta não encontrada.']);

            return $movement;
        });
    }

    private function getAtomicLockKey(int $account): String
    {
        return "atomic_lock_{$account}";
    }
}
