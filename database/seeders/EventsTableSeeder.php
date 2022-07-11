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

            foreach (range(1, random_int(10, 30)) as $i) {
                $eventMeta = [
                    'video' => Arr::random(['Video #1', 'Video #2', 'Video #3', 'Video #4', 'Video #5', 'Video #6']),
                    'page' => Arr::random(['Page #1', 'Page #2', 'Page #3', 'Page #4', 'Page #5', 'Page #6', 'Page #7']),
                    'pdf' => Arr::random(['PDF #1', 'PDF #2', 'PDF #3', 'PDF #4', 'PDF #5', 'PDF #6', 'PDF #7', 'PDF #8']),
                ];

                Event::factory()->create([
                    'app_id' => $app->id,
                    'show_id' => $app->show_id,
                    'client_id' => $app->client_id,
                    'attendee_id' => Arr::random($attendees),
                    'action_code' => Arr::random($actionCodes),
                    'event_code' => Arr::random($eventCodes),
                    'timestamp' => $show->start_date->addDays(random_int(0, $show->start_date->diffInDays($show->end_date)))->toDateTimeString(),
                    'meta' => [
                        $key = Arr::random(['video', 'page', 'pdf']) => $eventMeta[$key],
                    ],
                ]);
            }
        });
    }
}
