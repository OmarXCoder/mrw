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

        $response = $this->getJson(route('api.clients.shows.index', $client));

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

        $response = $this->getJson(route('api.clients.shows.show', [$client, $show]));

        $response->assertStatus(200);

        $response->assertJsonPath('data.name', $show->name);

        $response->assertJsonPath('data.client_id', $client->id);
    }

    /** @test */
    public function it_creates_a_show_for_a_specific_client()
    {
        $this->login();

        $show = Show::factory()->raw();

        $response = $this->postJson(route('api.clients.shows.store', $show['client_id']), $show);

        $response->assertCreated();

        $this->assertDatabaseCount('shows', 1);
    }


    /** @test */
    public function it_checks_for_required_fields_to_create_show()
    {
        $this->login();

        $show = Show::factory()->raw([
            'name' => null,
            'organizer' => null,
            'start_date' => null,
            'end_date' => null,
        ]);

        $response = $this->postJson(route('api.clients.shows.store', $show['client_id']), $show);

        $response->assertJsonValidationErrors(
            [
                'name',
                'organizer',
                'start_date',
                'end_date',
            ],
            'error.data'
        );
    }

    /** @test */
    public function it_requires_valid_start_and_end_dates()
    {
        $this->login();

        $show = Show::factory()->raw([
            'start_date' => 'not a valid date',
            'end_date' => 'not a valid date',
        ]);

        $response = $this->postJson(route('api.clients.shows.store', $show['client_id']), $show);

        $response->assertJsonValidationErrors(
            [
                'start_date',
                'end_date',
            ],
            'error.data'
        );
    }
}
