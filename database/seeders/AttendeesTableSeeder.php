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
            fn ($show) => Attendee::factory(random_int(10, 50))->create([
                'show_id' => $show->id,
            ])
        );
    }
}
