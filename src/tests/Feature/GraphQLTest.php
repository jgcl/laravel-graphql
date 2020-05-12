<?php

namespace Tests\Feature;

use Tests\TestCase;

class GraphQLTest extends TestCase
{
    /**
     * @return void
     */
    public function testGraphqlEndpointTest()
    {
        $response = $this->get('/graphql');

        $response->assertStatus(200);
    }
}
