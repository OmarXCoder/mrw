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

        // $actionCodes = ActionType::whereIn('name', ['opened', 'viewed', 'started'])->get()->pluck('code')->toArray();

        $eventCodes = EventType::whereIn('name', ['Page', 'Popup', 'PDF', 'Video'])->get()->pluck('code')->toArray();

        $pages = [
            ['title' => 'Page #1', 'topic' => 'PHP'],
            ['title' => 'Page #2', 'topic' => 'Javascript'],
            ['title' => 'Page #3', 'topic' => 'Ruby'],
            ['title' => 'Page #4', 'topic' => 'Python'],
            ['title' => 'Page #5', 'topic' => 'Java'],
            ['title' => 'Page #6', 'topic' => 'Dart'],
            ['title' => 'Page #7', 'topic' => 'Kotlin'],
            ['title' => 'Page #1', 'topic' => 'Web'],
            ['title' => 'Page #2', 'topic' => 'Web'],
            ['title' => 'Page #3', 'topic' => 'Web'],
            ['title' => 'Page #4', 'topic' => 'General'],
            ['title' => 'Page #5', 'topic' => 'Mobile'],
            ['title' => 'Page #6', 'topic' => 'Hybrid Apps'],
            ['title' => 'Page #7', 'topic' => 'Web Apps'],
            ['title' => 'Page #8', 'topic' => 'Mobile Apps'],
        ];

        $popups = [
            ['title' => 'PopUp #1', 'screen' => 'Screen #1'],
            ['title' => 'PopUp #2', 'screen' => 'Screen #1'],
            ['title' => 'PopUp #3', 'screen' => 'Screen #2'],
            ['title' => 'PopUp #4', 'screen' => 'Screen #2'],
            ['title' => 'PopUp #5', 'screen' => 'Screen #3'],
            ['title' => 'PopUp #6', 'screen' => 'Screen #3'],
            ['title' => 'PopUp #7', 'screen' => 'Screen #3'],
            ['title' => 'PopUp #8', 'screen' => 'Screen #3'],
        ];

        $PDFs = [
            ['title' => 'PDF #1', 'number_of_pages' => '2'],
            ['title' => 'PDF #2', 'number_of_pages' => '5'],
            ['title' => 'PDF #3', 'number_of_pages' => '4'],
            ['title' => 'PDF #4', 'number_of_pages' => '6'],
            ['title' => 'PDF #5', 'number_of_pages' => '8'],
            ['title' => 'PDF #6', 'number_of_pages' => '12'],
            ['title' => 'PDF #7', 'number_of_pages' => '9'],
            ['title' => 'PDF #8', 'number_of_pages' => '11'],
        ];

        $videos = [
            ['name' => 'Video #1', 'duration' => '04:30', 'source' => 'local'],
            ['name' => 'Video #2', 'duration' => '05:20', 'source' => 'youtube'],
            ['name' => 'Video #3', 'duration' => '02:12', 'source' => 'local'],
            ['name' => 'Video #4', 'duration' => '03:35', 'source' => 'youtube'],
            ['name' => 'Video #5', 'duration' => '06:07', 'source' => 'local'],
            ['name' => 'Video #6', 'duration' => '14:24', 'source' => 'youtube'],
            ['name' => 'Video #7', 'duration' => '20:00', 'source' => 'local'],
            ['name' => 'Video #1', 'duration' => '03:05', 'source' => 'youtube'],
            ['name' => 'Video #2', 'duration' => '07:08', 'source' => 'local'],
            ['name' => 'Video #3', 'duration' => '11:30', 'source' => 'youtube'],
            ['name' => 'Video #4', 'duration' => '06:42', 'source' => 'local'],
            ['name' => 'Video #5', 'duration' => '10:11', 'source' => 'youtube'],
            ['name' => 'Video #6', 'duration' => '30:34', 'source' => 'local'],
            ['name' => 'Video #7', 'duration' => '13:04', 'source' => 'youtube'],
        ];

        App::all()->each(function ($app) use ($eventCodes, $pages, $videos, $PDFs, $popups) {
            $attendees = $app->attendees->pluck('id')->toArray();
            $show = $app->show;

            if (empty($attendees)) {
                return;
            }

            foreach (range(1, random_int(10, 30)) as $i) {
                $eventMeta = [
                    4 => [
                        'actionCode' => Arr::random([0, 4]),
                        'meta' => Arr::random($pages),
                    ],
                    5 => [
                        'actionCode' => Arr::random([5, 4]),
                        'meta' => Arr::random($popups),
                    ],
                    6 => [
                        'actionCode' => Arr::random([3, 4]),
                        'meta' => Arr::random($PDFs),
                    ],
                    7 => [
                        'actionCode' => Arr::random([1, 2]),
                        'meta' => Arr::random($videos),
                    ],
                ];

                Event::factory()->create([
                    'app_id' => $app->id,
                    'show_id' => $app->show_id,
                    'client_id' => $app->client_id,
                    'attendee_id' => Arr::random($attendees),
                    'event_code' => $eventCode = Arr::random($eventCodes),
                    'action_code' => $eventMeta[$eventCode]['actionCode'],
                    'timestamp' => $show->start_date->addDays(random_int(0, $show->start_date->diffInDays($show->end_date)))->toDateTimeString(),
                    'meta' => $eventMeta[$eventCode]['meta'] ?? ['key' => 'value'],
                ]);
            }
        });
    }
}
