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

        $actionCodes = ActionType::whereIn('name', ['opened', 'viewed', 'started'])->get()->pluck('code')->toArray();

        $eventCodes = EventType::whereIn('name', ['video', 'page', 'pdf'])->get()->pluck('code')->toArray();

        $pages = [
            ['title' => 'Page #1', 'group' => 'Group #1'],
            ['title' => 'Page #2', 'group' => 'Group #2'],
            ['title' => 'Page #3', 'group' => 'Group #1'],
            ['title' => 'Page #4', 'group' => 'Group #2'],
            ['title' => 'Page #5', 'group' => 'Group #1'],
            ['title' => 'Page #6', 'group' => 'Group #2'],
            ['title' => 'Page #7', 'group' => 'Group #1'],
            ['title' => 'Page #1', 'group' => 'Group #2'],
            ['title' => 'Page #2', 'group' => 'Group #1'],
            ['title' => 'Page #3', 'group' => 'Group #2'],
            ['title' => 'Page #4', 'group' => 'Group #1'],
            ['title' => 'Page #5', 'group' => 'Group #2'],
            ['title' => 'Page #6', 'group' => 'Group #1'],
            ['title' => 'Page #7', 'group' => 'Group #2'],
        ];

        $videos = [
            ['title' => 'Video #1', 'path' => 'video-1.mp4', 'source' => 'local'],
            ['title' => 'Video #2', 'path' => 'video-2.mp4', 'source' => 'youtube'],
            ['title' => 'Video #3', 'path' => 'video-3.mp4', 'source' => 'local'],
            ['title' => 'Video #4', 'path' => 'video-4.mp4', 'source' => 'youtube'],
            ['title' => 'Video #5', 'path' => 'video-5.mp4', 'source' => 'local'],
            ['title' => 'Video #6', 'path' => 'video-6.mp4', 'source' => 'youtube'],
            ['title' => 'Video #7', 'path' => 'video-7.mp4', 'source' => 'local'],
            ['title' => 'Video #1', 'path' => 'video-1.mp4', 'source' => 'youtube'],
            ['title' => 'Video #2', 'path' => 'video-2.mp4', 'source' => 'local'],
            ['title' => 'Video #3', 'path' => 'video-3.mp4', 'source' => 'youtube'],
            ['title' => 'Video #4', 'path' => 'video-4.mp4', 'source' => 'local'],
            ['title' => 'Video #5', 'path' => 'video-5.mp4', 'source' => 'youtube'],
            ['title' => 'Video #6', 'path' => 'video-6.mp4', 'source' => 'local'],
            ['title' => 'Video #7', 'path' => 'video-7.mp4', 'source' => 'youtube'],
        ];

        $PDFs = [
            ['title' => 'PDF #1', 'path' => 'file-1.pdf', 'group' => 'Group #1'],
            ['title' => 'PDF #2', 'path' => 'file-2.pdf', 'group' => 'Group #2'],
            ['title' => 'PDF #3', 'path' => 'file-3.pdf', 'group' => 'Group #1'],
            ['title' => 'PDF #4', 'path' => 'file-4.pdf', 'group' => 'Group #2'],
            ['title' => 'PDF #5', 'path' => 'file-5.pdf', 'group' => 'Group #1'],
            ['title' => 'PDF #6', 'path' => 'file-6.pdf', 'group' => 'Group #2'],
            ['title' => 'PDF #7', 'path' => 'file-7.pdf', 'group' => 'Group #1'],
            ['title' => 'PDF #1', 'path' => 'file-1.pdf', 'group' => 'Group #2'],
            ['title' => 'PDF #2', 'path' => 'file-2.pdf', 'group' => 'Group #1'],
            ['title' => 'PDF #3', 'path' => 'file-3.pdf', 'group' => 'Group #2'],
            ['title' => 'PDF #4', 'path' => 'file-4.pdf', 'group' => 'Group #1'],
            ['title' => 'PDF #5', 'path' => 'file-5.pdf', 'group' => 'Group #2'],
            ['title' => 'PDF #6', 'path' => 'file-6.pdf', 'group' => 'Group #1'],
            ['title' => 'PDF #7', 'path' => 'file-7.pdf', 'group' => 'Group #2'],
        ];

        App::all()->each(function ($app) use ($actionCodes, $eventCodes, $pages, $videos, $PDFs) {
            $attendees = $app->attendees->pluck('id')->toArray();
            $show = $app->show;

            if (empty($attendees)) {
                return;
            }

            foreach (range(1, random_int(10, 30)) as $i) {
                $eventMeta = [
                    4 => Arr::random($pages),
                    6 => Arr::random($PDFs),
                    7 => Arr::random($videos),
                ];

                Event::factory()->create([
                    'app_id' => $app->id,
                    'show_id' => $app->show_id,
                    'client_id' => $app->client_id,
                    'attendee_id' => Arr::random($attendees),
                    'action_code' => Arr::random($actionCodes),
                    'event_code' => $eventCode = Arr::random($eventCodes),
                    'timestamp' => $show->start_date->addDays(random_int(0, $show->start_date->diffInDays($show->end_date)))->toDateTimeString(),
                    'meta' => $eventMeta[$eventCode] ?? ['key' => 'value'],
                ]);
            }
        });
    }
}
