<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listOfPermissions = [
            'create_clients',
            'create_shows',
            'create_apps',
            'view_clients',
            'view_shows',
            'view_own_shows',
            'view_own_team',
        ];

        $permissions = collect($listOfPermissions)->map(
            fn ($permission) => [
                'name' => $permission,
                'guard_name' => 'web',
            ]
        );

        Permission::insert($permissions->toArray());

        Role::create(['name' => 'system_admin'])
            ->givePermissionTo([
                'create_clients',
                'create_shows',
                'create_apps',
                'view_clients',
                'view_shows',
            ]);

        Role::create(['name' => 'client_admin'])
            ->givePermissionTo([
                'view_own_shows',
                'view_own_team',
            ]);
    }
}
