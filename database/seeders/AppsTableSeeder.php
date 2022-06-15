<?php
namespace Database\Seeders;

use App\Models\App;
use App\Models\Show;
use Illuminate\Database\Seeder;

class AppsTableSeeder extends Seeder
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
            fn ($show) => App::factory(random_int(1, 6))->create([
                'show_id' => $show->id,
                'client_id' => $show->client_id,
                'created_at' => $show->created_at->addDays(random_int(1, 5)),
            ])
        );
    }
}
