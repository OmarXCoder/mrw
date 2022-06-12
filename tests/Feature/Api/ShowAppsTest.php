<?php

namespace Tests\Feature\Api;

use App\Models\App;
use App\Models\Show;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowAppsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_a_paginated_list_of_show_apps()
    {
        $this->login();

        $show = Show::factory()->create();

        App::factory(3)->create(['show_id' => $show->id]);

        $response = $this->getJson(route('api.shows.apps.index', $show));

        $response->assertStatus(200);

        $response->assertJsonStructure(['data', 'meta', 'links']);

        $response->assertJsonPath('data.0.show_id', $show->id);
    }

    /** @test */
    public function it_creates_an_app_for_a_specific_show()
    {
        $this->login();

        $app = App::factory()->raw();

        $response = $this->postJson(route('api.shows.apps.store', $app['show_id']), $app);

        $response->assertCreated();

        $this->assertDatabaseCount('apps', 1);
    }

    /** @test */
    public function it_checks_for_required_fields_to_create_an_app()
    {
        $this->login();

        $app = App::factory()->raw([
            'name' => null,
            'kiosk_id' => null,
            'machine_id' => null,
        ]);

        $response = $this->postJson(route('api.shows.apps.store', $app['show_id']), $app);

        $response->assertJsonValidationErrors(
            [
                'name',
                'kiosk_id',
                'machine_id',
            ],
            'error.data'
        );
    }
}
