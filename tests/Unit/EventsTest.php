<?php
namespace Tests\Unit;

use App\Models\ActionType;
use App\Models\Event;
use App\Models\EventType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_ensures_the_event_belongs_to_the_same_client_as_the_app_it_occured_on()
    {
        $event = Event::factory()->create([
            'action_code' => ActionType::factory(),
            'event_code' => EventType::factory(),
            'client_id' => null,
        ]);

        $this->assertEquals($event->client_id, $event->app->client_id);
    }

    /**
     * @test
     */
    public function it_ensures_the_event_belongs_to_the_same_show_as_the_app_it_occured_on()
    {
        $event = Event::factory()->create([
            'action_code' => ActionType::factory(),
            'event_code' => EventType::factory(),
            'show_id' => null,
        ]);

        $this->assertEquals($event->show_id, $event->app->show_id);
    }
}
