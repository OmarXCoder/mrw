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
            'show_id' => null,
        ]);

        $response = $this->postJson(route('api.attendees.store'), $attendee);

        $response->assertJsonValidationErrors(
            [
                'badge_id',
                'first_name',
                'last_name',
                'email',
                'show_id',
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

    /** @test */
    public function it_updates_an_attendee()
    {
        $this->login();

        $attendee = Attendee::factory()->create();

        $data = [
            'first_name' => 'new first name',
            'last_name' => 'new last name',
        ];

        $response = $this->patchJson(route('api.attendees.update', $attendee), $data);

        $response->assertSuccessful();

        $this->assertEquals($data['first_name'], $attendee->refresh()->first_name);
    }

    /** @test */
    public function it_makes_sure_the_email_is_unique_on_update()
    {
        $this->login();

        Attendee::factory()->create(['email' => 'email@example.com']);

        $attendee = Attendee::factory()->create();

        $data = [
            'email' => 'email@example.com',
        ];

        $response = $this->patchJson(route('api.attendees.update', $attendee), $data);

        $response->assertJsonValidationErrors([
            'email',
        ], 'error.data');
    }

    /** @test */
    public function it_updates_only_provided_fields_and_returns_updated_attendee()
    {
        $this->login();

        $attendee = Attendee::factory()->create();

        $data = [
            'first_name' => 'new first name',
        ];

        $response = $this->patchJson(route('api.attendees.update', $attendee), $data);

        $response->assertJsonPath('data.first_name', $data['first_name']);

        $response->assertJsonPath('data.email', $attendee->email);
    }
}
