<?php
namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 21) as $i) {
            Client::factory()->create([
                'created_at' => today()->subDays(random_int(0, 120)),
            ]);
        }
    }
}
