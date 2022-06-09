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
            'name' => 'Admin',
            'email' => 'admin@admin.com',
        ]);

        $clientAdmin = User::factory()->create([
            'name' => 'User',
            'email' => 'user@admin.com',
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
        $clientAdmin->assignRole('client_admin');
    }
}
