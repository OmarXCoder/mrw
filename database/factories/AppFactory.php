<?php
namespace Database\Factories;

use App\Models\Client;
use App\Models\Show;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\App>
 */
class AppFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'       => $this->faker->word(),
            'client_id'  => Client::factory(),
            'show_id'    => Show::factory(),
            'kiosk_id'   => $this->faker->words(2, true),
            'machine_id' => $this->faker->uuid(),
        ];
    }
}
