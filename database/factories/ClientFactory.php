<?php
namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
      * Configure the model factory.
      *
      * @return $this
      */
    public function configure()
    {
        return $this->afterCreating(function (Client $client) {
            $user = User::factory()->create([
                'client_id' => $client->id,
            ]);

            if (Role::whereName('client_admin')->exists()) {
                $user->assignRole('client_admin');
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
        ];
    }
}
