<?php
namespace Database\Seeders;

use App\Models\Client;
use App\Models\Show;
use Illuminate\Database\Seeder;

class ShowsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = Client::all();

        $clients->each(
            fn ($client) => Show::factory(random_int(1, 6))->create(['client_id' => $client->id])
        );
    }
}
