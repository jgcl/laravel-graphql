<?php

namespace Tests\Unit;

use App\Movement;
use App\Repositories\MovementRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MovementRepositoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $movementRepository;
    protected $movement;

    public function setUp() :void
    {
        parent::setUp();

        $this->movementRepository = new MovementRepository();

        $this->movement = new Movement([
            'account' => 10001,
            'amount' => 100,
            'balance' => 100,
            'created_at' => now(),
        ]);
    }

    /**
     * @return void
     */
    public function testGetLasMovement()
    {
        $expected = null;
        $actual = $this->movementRepository->getLastMovement(10000);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function testCreateAndLastMovement()
    {
        $actual = $this->movementRepository->createMovement($this->movement);
        $this->assertEquals(
            $this->movement->only(['account', 'amount', 'balance']),
            $actual->only(['account', 'amount', 'balance'])
        );

        $actual = $this->movementRepository->getLastMovement($this->movement->account);
        $this->assertEquals(
            $this->movement->only(['account', 'amount', 'balance']),
            $actual->only(['account', 'amount', 'balance'])
        );
    }
}
