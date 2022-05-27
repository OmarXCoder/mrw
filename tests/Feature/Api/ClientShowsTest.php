<?php
namespace Tests\Feature\Api;

use App\Models\Client;
use App\Models\Show;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientShowsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_a_paginated_list_of_client_shows()
    {
        $this->login();

        $client = Client::factory()->create();

        Show::factory(3)->create(['client_id' => $client->id]);

        $response = $this->getJson(route('api.client.shows.index', $client));

        $response->assertStatus(200);

        $response->assertJsonStructure(['data', 'meta', 'links']);

        $response->assertJsonPath('data.0.client_id', $client->id);
    }

    /** @test */
    public function it_can_fetch_a_show_for_a_specific_client_by_id()
    {
        $this->login();

        $client = Client::factory()->create();

        $show = Show::factory()->create(['client_id' => $client->id]);

        $response = $this->getJson(route('api.client.shows.show', [$client, $show]));

        $response->assertStatus(200);

        $response->assertJsonPath('data.name', $show->name);

        $response->assertJsonPath('data.client_id', $client->id);
    }
}
