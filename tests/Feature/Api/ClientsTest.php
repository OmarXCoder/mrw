<?php
namespace Tests\Feature\Api;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_a_paginated_list_of_clients()
    {
        $this->login();

        Client::factory(3)->create();

        $response = $this->getJson(route('api.clients.index'));

        $response->assertStatus(200);

        $response->assertJsonStructure(['data', 'meta', 'links']);
    }

    /** @test */
    public function it_fetches_a_single_client_by_id()
    {
        $this->login();

        $client = Client::factory()->create();

        $response = $this->getJson(route('api.clients.show', $client));

        $response->assertStatus(200);

        $response->assertJsonPath('data.name', $client->name);
    }
}
