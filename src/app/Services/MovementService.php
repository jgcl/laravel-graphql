<?php

namespace App\Services;

use App\Movement;
use App\Repositories\MovementRepository;
use Illuminate\Contracts\Cache\LockTimeoutException;
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
        $lock = Cache::lock('foo', 10);

        try {
            $lock->block(5);

            // Lock acquired after waiting maximum of 5 seconds...
            $lastMovement = $this->movementRepository->getLastMovement($account);

            if ($amount > $lastMovement->balance) {
                throw ValidationException::withMessages(['balance' => 'Saldo insuficiente']);
            }

            $requestMovement = new Movement();
            $requestMovement->account = $account;
            $requestMovement->amount = $amount * -1;

            return $this->movementRepository->createMovement($requestMovement);
        } catch (LockTimeoutException $e) {
            // Unable to acquire lock...
            throw $e;
        } finally {
            optional($lock)->release();
        }
    }

    public function deposit(int $account, int $amount): Movement
    {
        $lock = Cache::lock('foo', 10);

        try {
            $lock->block(5);

            // Lock acquired after waiting maximum of 5 seconds...
            $requestMovement = new Movement();
            $requestMovement->account = $account;
            $requestMovement->amount = $amount;
            return $this->movementRepository->createMovement($requestMovement);
        } catch (LockTimeoutException $e) {
            // Unable to acquire lock...
            throw $e;
        } finally {
            optional($lock)->release();
        }
    }

    public function balance(int $account): Movement
    {
        $lock = Cache::lock('foo', 10);

        try {
            $lock->block(5);

            // Lock acquired after waiting maximum of 5 seconds...
            $movement = $this->movementRepository->getLastMovement($account);

            if (empty($movement)) {
                throw ValidationException::withMessages(['account' => 'Conta não encontrada.']);
            }

            return $movement;
        } catch (LockTimeoutException $e) {
            // Unable to acquire lock...
            throw $e;
        } finally {
            optional($lock)->release();
        }
    }

    private function getAtomicLockKey(int $account): string
    {
        return "atomic_lock_{$account}";
    }
}
