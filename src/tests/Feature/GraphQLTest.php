<?php

namespace Tests\Feature;

use App\Services\MovementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GraphQLTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $movementService;

    public function setUp() :void
    {
        parent::setUp();

        $this->movementService = new MovementService();
    }

    /**
     * @return void
     */
    public function testGraphqlEndpointTest()
    {
        $response = $this->get('/graphql');

        $response->assertStatus(200);
    }

    public function testDepositarMutation()
    {
        $response = $this->json('POST', '/graphql', [
            'query' => "mutation { depositar(conta:1, valor:50.00) { conta, saldo } }"
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'depositar' => [
                        'conta' => true,
                        'saldo' => true
                    ]
                ]
            ]);
    }

    public function testSacarMutation()
    {
        $this->movementService->deposit(1, 20.00);

        $response = $this->json('POST', '/graphql', [
            'query' => "mutation { sacar(conta:1, valor:10.00) { conta, saldo } }"
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'sacar' => [
                        'conta' => true,
                        'saldo' => true
                    ]
                ]
            ]);
    }

    public function testSaldoQuery()
    {
        $this->movementService->deposit(1, 10);

        $response = $this->json('POST', '/graphql', [
            'query' => "query { saldo(conta:1) }"
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'saldo' => true
                ]
            ]);
    }
}
