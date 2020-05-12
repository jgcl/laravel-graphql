<?php

namespace Tests\Unit;

use App\Movement;
use App\Repositories\MovementRepository;
use App\Services\MovementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MovementServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $movementRepository;
    protected $movementService;
    protected $movement;

    public function setUp() :void
    {
        parent::setUp();

        $this->movementRepository = new MovementRepository();
        $this->movementService = new MovementService();

        $this->movement = new Movement([
            'account' => 10002,
            'amount' => 100,
            'balance' => 100,
            'created_at' => now(),
        ]);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testDeposit()
    {
        $actual = $this->movementService->deposit($this->movement->account, $this->movement->amount);
        $this->assertGreaterThan(0, $actual->balance);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testBalanceAccountNotFound()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->movementService->balance($this->movement->account+1);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testBalance()
    {
        $this->movementService->deposit($this->movement->account, $this->movement->amount);
        $actual = $this->movementService->balance($this->movement->account);
        $this->assertGreaterThanOrEqual(0, $actual->balance);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testWithdrawAccountNotFound()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $actual = $this->movementService->withdraw($this->movement->account, $this->movement->amount);
        $this->assertGreaterThan(0, $actual->balance);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testWithdrawInsufficientFunds()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->movementService->deposit($this->movement->account, $this->movement->amount-10);
        $this->movementService->withdraw($this->movement->account, $this->movement->amount);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testWithdraw()
    {
        $this->movementService->deposit($this->movement->account, $this->movement->amount);
        $actual = $this->movementService->withdraw($this->movement->account, $this->movement->amount);
        $this->assertGreaterThanOrEqual(0, $actual->balance);
    }
}
