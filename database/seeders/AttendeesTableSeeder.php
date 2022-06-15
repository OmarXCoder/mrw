<?php
namespace Database\Seeders;

use App\Models\Attendee;
use App\Models\Show;
use Illuminate\Database\Seeder;

class AttendeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shows = Show::all();

        $shows->each(
            fn ($show) => Attendee::factory(random_int(10, 35))->create([
                'show_id' => $show->id,
                'client_id' => $show->client_id,
                'created_at' => $show->start_date->addDays(
                    random_int(0, $show->start_date->diffInDays($show->end_date))
                ),
            ])
        );
    }
}
