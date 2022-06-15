<?php
namespace Database\Seeders;

use App\Models\ActionType;
use App\Models\App;
use App\Models\Event;
use App\Models\EventType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ActionTypesTableSeeder::class,
            EventTypesTableSeeder::class,
        ]);

        $actionCodes = ActionType::all()->pluck('code')->toArray();

        $eventCodes = EventType::all()->pluck('code')->toArray();

        App::all()->each(function ($app) use ($actionCodes, $eventCodes) {
            $attendees = $app->attendees->pluck('id')->toArray();
            $show = $app->show;

            if (empty($attendees)) {
                return;
            }

            Event::factory(random_int(10, 30))->create([
                'app_id' => $app->id,
                'show_id' => $app->show_id,
                'client_id' => $app->client_id,
                'attendee_id' => Arr::random($attendees),
                'action_code' => Arr::random($actionCodes),
                'event_code' => Arr::random($eventCodes),
                'timestamp' => $show->start_date->addDays(random_int(0, $show->start_date->diffInDays($show->end_date)))->toDateTimeString(),
            ]);
        });
    }
}
