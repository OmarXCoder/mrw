<?php
namespace Database\Factories;

use App\Models\Client;
use App\Models\Show;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Show>
 */
class ShowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'organizer' => $this->faker->company(),
            'start_date' => $this->faker->dateTime(),
            'end_date' => $this->faker->dateTime(),
            'status' => $this->faker->randomElement(Show::statuses()),
            'client_id' => Client::factory(),
        ];
    }
}
