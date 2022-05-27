<?php
namespace Tests\Feature\Api;

use Database\Seeders\ActionTypesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ActionTypesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_a_list_of_all_action_types()
    {
        $this->login();

        $this->seed(ActionTypesTableSeeder::class);

        $response = $this->getJson(route('api.action-types.index'));

        $response->assertStatus(200);

        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 7)
                ->has(
                    'data.0',
                    fn ($json) => $json
                        ->where('code', '0')
                        ->where('name', 'viewed')
                        ->etc()
                )
                ->has(
                    'data.6',
                    fn ($json) => $json
                        ->where('code', '6')
                        ->where('name', 'reset')
                        ->etc()
                )
        );
    }
}
