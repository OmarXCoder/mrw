<?php
namespace Database\Factories;

use App\Models\App;
use App\Models\Attendee;
use App\Models\Show;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'app_id' => App::factory(),
            'attendee_id' => Attendee::factory(),
            'show_id' => Show::factory(),
            'action_code' => Arr::random(range(0, 6)),
            'event_code' => Arr::random(range(0, 13)),
            'timestamp' => \Carbon\Carbon::now()->toDateTimeString(),
            'data' => json_encode([
                'machine_id' => '351cx-132c234-xwaeu2-sxl2',
                'kiosk_id' => 'Back Wall, Left',
            ]),
        ];
    }
}
