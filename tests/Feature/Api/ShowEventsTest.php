<?php
namespace Tests\Feature\Api;

use App\Models\App;
use App\Models\Event;
use App\Models\Show;
use Database\Seeders\ActionTypesTableSeeder;
use Database\Seeders\EventTypesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowEventsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_a_paginated_list_of_show_events()
    {
        $this->login();

        $this->seed([
            ActionTypesTableSeeder::class,
            EventTypesTableSeeder::class,
        ]);

        $show = Show::factory()->create();

        $app = App::factory()->create(['show_id' => $show->id]);

        Event::factory(3)->create(['app_id' => $app->id]);

        $response = $this->getJson(route('api.shows.events.index', $show));

        $response->assertStatus(200);

        $response->assertJsonStructure(['data', 'meta', 'links']);

        $response->assertJsonPath('data.0.show_id', $show->id);
    }
}
