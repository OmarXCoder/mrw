<?php
namespace Tests\Feature\Api;

use App\Models\Event;
use Database\Seeders\ActionTypesTableSeeder;
use Database\Seeders\EventTypesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_tracks_attendee_actions()
    {
        $this->login();

        $this->seed([
            ActionTypesTableSeeder::class,
            EventTypesTableSeeder::class,
        ]);

        $this->withoutExceptionHandling();

        $event = Event::factory()->raw();

        $response = $this->postJson(route('api.events.store'), $event);

        $response->assertCreated();

        $this->assertDatabaseCount('events', 1);
    }

    /** @test */
    public function it_validates_event_fields()
    {
        $this->login();

        $event = Event::factory()->raw([
            'app_id' => null,
            'show_id' => null,
            'client_id' => null,
            'action_code' => null,
            'event_code' => null,
            'timestamp' => null,
        ]);

        $response = $this->postJson(route('api.events.store'), $event);

        $response->assertJsonValidationErrors(
            [
                'app_id',
                'show_id',
                'client_id',
                'action_code',
                'event_code',
                'timestamp',
            ],
            'error.data'
        );
    }

    /** @test */
    public function event_must_have_a_valid_timestamp()
    {
        $this->login();

        $event = Event::factory()->raw([
            'timestamp' => 'invalid date',
        ]);

        $response = $this->postJson(route('api.events.store'), $event);

        $response->assertJsonValidationErrors(
            [
                'timestamp',
            ],
            'error.data'
        );
    }
}
