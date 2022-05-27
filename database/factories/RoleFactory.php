<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $roles = [
            'owner'        => 'Owner',
            'admin'        => 'Admin',
            'client'       => 'Client',
            'show_manager' => 'Show Manager',
        ];

        return [
            'name'         => $role = $this->faker->randomElement(array_keys($roles)),
            'display_name' => $roles[$role],
        ];
    }
}
