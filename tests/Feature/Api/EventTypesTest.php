<?php
namespace Tests\Feature\Api;

use Database\Seeders\EventTypesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class EventTypesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_a_list_of_all_event_types()
    {
        $this->login();

        $this->seed(EventTypesTableSeeder::class);

        $response = $this->getJson(route('api.event-types.index'));

        $response->assertStatus(200);

        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 14)
                ->has(
                    'data.0',
                    fn ($json) => $json
                        ->where('code', '0')
                        ->where('name', 'Error')
                        ->etc()
                )
                ->has(
                    'data.13',
                    fn ($json) => $json
                        ->where('code', '13')
                        ->where('name', 'Custom')
                        ->etc()
                )
        );
    }
}
