<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MovementModelTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @return void
     */
    public function testMovementsDatabaseHasExpectedColumns()
    {
        $this->assertTrue(
            Schema::hasColumns('movements', [
                'id', 'account', 'amount', 'balance', 'created_at'
            ]), 1);
    }
}
