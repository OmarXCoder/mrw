<?php
namespace Tests\Feature\Api;

use App\Models\Attendee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendeesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_registers_attendees()
    {
        $this->login();

        $this->withoutExceptionHandling();

        $attendee = Attendee::factory()->raw();

        $response = $this->postJson(route('api.attendees.store'), $attendee);

        $response->assertCreated();

        $this->assertDatabaseCount('attendees', 1);
    }

    /** @test */
    public function it_validates_attendees_registration_fields()
    {
        $this->login();

        $attendee = Attendee::factory()->raw([
            'badge_id' => null,
            'first_name' => null,
            'last_name' => null,
            'email' => null,
        ]);

        $response = $this->postJson(route('api.attendees.store'), $attendee);

        $response->assertJsonValidationErrors(
            [
                'badge_id',
                'first_name',
                'last_name',
                'email',
            ],
            'error.data'
        );
    }

    /** @test */
    public function it_registers_an_attendee_once_per_show()
    {
        $this->login();

        $this->withoutExceptionHandling();

        $attendee = Attendee::factory()->raw();

        $this->postJson(route('api.attendees.store'), $attendee);

        $this->assertDatabaseCount('attendees', 1);

        $this->postJson(route('api.attendees.store'), $attendee);

        // The count of attendees in the DB still 1
        $this->assertDatabaseCount('attendees', 1);
    }

    /** @test */
    public function it_can_fetch_an_attendee_by_id()
    {
        $this->login();

        $attendee = Attendee::factory()->create();

        $response = $this->getJson(route('api.attendees.show', $attendee));

        $response->assertStatus(200);

        $response->assertJsonPath('data.first_name', $attendee->first_name);
        $response->assertJsonPath('data.email', $attendee->email);
    }
}
