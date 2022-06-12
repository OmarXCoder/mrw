<?php

namespace Tests\Feature\Api;

use App\Models\App;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_a_paginated_list_of_all_apps_in_the_system()
    {
        $this->login();

        App::factory(3)->create();

        $response = $this->getJson(route('api.apps.index'));

        $response->assertStatus(200);

        $response->assertJsonStructure(['data', 'meta', 'links']);
    }

    /** @test */
    public function it_fetches_a_single_app_by_id()
    {
        $this->login();

        $app = App::factory()->create();

        $response = $this->getJson(route('api.apps.show', $app));

        $response->assertStatus(200);

        $response->assertJsonPath('data.name', $app->name);
    }
}
