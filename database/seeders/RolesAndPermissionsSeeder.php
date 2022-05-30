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
            // Roles
            ['roles.create', 'Create roles', false],
            ['roles.view', 'View roles', false],
            ['roles.edit', 'Edit roles', false],
            ['roles.delete', 'Delete roles', false],
            // Permissions
            ['permissions.create', 'Create permissions', false],
            ['permissions.view', 'View permissions', false],
            ['permissions.edit', 'Edit permissions', false],
            ['permissions.delete', 'Delete permissions', false],
            // Clients
            ['clients.create', 'Create clients', false],
            ['clients.view', 'View clients', false],
            ['clients.edit', 'Edit clients', false],
            ['clients.delete', 'Delete clients', false],
            // Shows
            ['shows.create', 'Create shows', false],
            ['shows.view', 'View shows', true],
            ['shows.edit', 'Edit shows', false],
            ['shows.delete', 'Delete shows', false],
            // Apps
            ['apps.create', 'Create apps', false],
            ['apps.view', 'View apps', true],
            ['apps.edit', 'Edit apps', false],
            ['apps.delete', 'Delete apps', false],
            // Attendees
            ['attendees.create', 'Create attendees', false],
            ['attendees.view', 'View attendees', true],
            ['attendees.edit', 'Edit attendees', false],
            ['attendees.delete', 'Delete attendees', false],
            // Events
            ['events.view', 'View events', true],
            ['events.delete', 'Delete events', true],
            // EventTypes
            ['event_types.create', 'Create event types', false],
            ['event_types.view', 'View event types', false],
            ['event_types.edit', 'Edit event types', false],
            ['event_types.delete', 'Delete event types', false],
            // ActionTypes
            ['action_types.create', 'Create action types', false],
            ['action_types.view', 'View action types', false],
            ['action_types.edit', 'Edit action types', false],
            ['action_types.delete', 'Delete action types', false],
        ];

        $permissions = collect($listOfPermissions)->map(
            fn ($permission) => [
                'name' => $permission[0],
                'label' => $permission[1],
                'available_to_clients' => $permission[2],
                'guard_name' => 'web',
            ]
        );

        Permission::insert($permissions->toArray());

        Role::create(['name' => 'system_admin', 'label' => 'System Admin', 'is_client_role' => false])
            ->givePermissionTo(Permission::all());

        Role::create(['name' => 'client_admin', 'label' => 'Admin', 'is_client_role' => true])
            ->givePermissionTo([
                'shows.view',
                'apps.view',
                'attendees.view',
                'events.view',
            ]);
    }
}
