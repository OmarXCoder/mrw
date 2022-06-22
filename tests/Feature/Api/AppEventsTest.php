<?php
namespace Tests\Feature\Api;

use App\Models\App;
use App\Models\Event;
use Database\Seeders\ActionTypesTableSeeder;
use Database\Seeders\EventTypesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppEventsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_a_paginated_list_of_app_events()
    {
        $this->login();

        $this->seed([
            ActionTypesTableSeeder::class,
            EventTypesTableSeeder::class,
        ]);

        $app = App::factory()->create();

        Event::factory(3)->create(['app_id' => $app->id]);

        $response = $this->getJson(route('api.apps.events.index', $app));

        $response->assertStatus(200);

        $response->assertJsonStructure(['data', 'meta', 'links']);

        $response->assertJsonPath('data.0.app_id', $app->id);

        $response->assertJsonPath('data.0.show_id', $app->show_id);
    }
}
