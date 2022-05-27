<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
        ]);

        $this->call([
            RolesAndPermissionsSeeder::class,
            ClientsTableSeeder::class,
            ShowsTableSeeder::class,
            AppsTableSeeder::class,
            AttendeesTableSeeder::class,
            AppAttendeeTableSeeder::class,
            EventsTableSeeder::class,
        ]);

        $superAdmin->assignRole('system_admin');
    }
}
