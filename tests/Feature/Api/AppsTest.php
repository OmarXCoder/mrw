<?php
namespace Tests\Feature\Api;

use App\Models\App;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_registers_apps_in_the_db()
    {
        $this->login();

        $this->withoutExceptionHandling();

        $app = App::factory()->raw();

        $response = $this->postJson(route('api.apps.store'), $app);

        $response->assertCreated();

        $this->assertDatabaseCount('apps', 1);
    }

    /** @test */
    public function it_validates_app_data()
    {
        $this->login();

        $response = $this->postJson(route('api.apps.store'), []);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(
                [
                    'name',
                    'client_id',
                    'show_id',
                ],
                'error.data'
            );
    }

    /** @test */
    public function apps_are_registered_only_for_the_first_connection_to_the_api()
    {
        $this->login();

        $app = App::factory()->raw();

        $this->postJson(route('api.apps.store'), $app);

        // Subsquent connections doesn't reregister the app
        $this->postJson(route('api.apps.store'), $app);

        $this->assertDatabaseCount('apps', 1);
    }
}
