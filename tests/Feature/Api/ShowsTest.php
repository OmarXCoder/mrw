<?php
namespace Tests\Feature\Api;

use App\Models\Show;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_a_paginated_list_of_shows()
    {
        $this->login();

        Show::factory(3)->create();

        $response = $this->getJson(route('api.shows.index'));

        $response->assertStatus(200);

        $response->assertJsonStructure(['data', 'meta', 'links']);
    }

    /** @test */
    public function it_can_fetch_a_show_by_id()
    {
        $this->login();

        $show = Show::factory()->create();

        $response = $this->getJson(route('api.shows.show', $show));

        $response->assertStatus(200);

        $response->assertJsonPath('data.name', $show->name);
    }
}
