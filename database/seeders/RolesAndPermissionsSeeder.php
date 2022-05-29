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
            ['roles_create', 'Create roles', false],
            ['roles_view', 'View roles', false],
            ['roles_edit', 'Edit roles', false],
            ['roles_delete', 'Delete roles', false],
            // Permissions
            ['permissions_create', 'Create permissions', false],
            ['permissions_view', 'View permissions', false],
            ['permissions_edit', 'Edit permissions', false],
            ['permissions_delete', 'Delete permissions', false],
            // Clients
            ['clients_create', 'Create clients', false],
            ['clients_view', 'View clients', false],
            ['clients_edit', 'Edit clients', false],
            ['clients_delete', 'Delete clients', false],
            // Shows
            ['shows_create', 'Create shows', false],
            ['shows_view', 'View shows', true],
            ['shows_edit', 'Edit shows', false],
            ['shows_delete', 'Delete shows', false],
            // Apps
            ['apps_create', 'Create apps', false],
            ['apps_view', 'View apps', true],
            ['apps_edit', 'Edit apps', false],
            ['apps_delete', 'Delete apps', false],
            // Attendees
            ['attendees_create', 'Create attendees', false],
            ['attendees_view', 'View attendees', true],
            ['attendees_edit', 'Edit attendees', false],
            ['attendees_delete', 'Delete attendees', false],
            // Events
            ['events_view', 'View events', true],
            ['events_delete', 'Delete events', true],
            // EventTypes
            ['event_types_create', 'Create event types', false],
            ['event_types_view', 'View event types', false],
            ['event_types_edit', 'Edit event types', false],
            ['event_types_delete', 'Delete event types', false],
            // ActionTypes
            ['action_types_create', 'Create action types', false],
            ['action_types_view', 'View action types', false],
            ['action_types_edit', 'Edit action types', false],
            ['action_types_delete', 'Delete action types', false],
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
                'shows_view',
                'apps_view',
                'attendees_view',
                'events_view',
            ]);
    }
}
