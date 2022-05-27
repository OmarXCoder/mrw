<?php
namespace Database\Seeders;

use App\Models\Show;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class AppAttendeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shows = Show::all();

        $shows->each(function (Show $show) {
            $apps = $show->apps;
            $attendees = $show->attendees->pluck('id')->toArray();

            $apps->each(
                fn ($app) => $app->attendees()->attach(
                    Arr::random(
                        $attendees,
                        random_int(0, count($attendees))
                    )
                )
            );
        });
    }
}
