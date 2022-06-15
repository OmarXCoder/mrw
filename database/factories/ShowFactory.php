<?php
namespace Database\Factories;

use App\Models\Client;
use App\Models\Show;
use Carbon\Carbon;
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
            'start_date' => $start_date = Carbon::create($this->faker->dateTimeThisYear('+5 months')),
            'end_date' => $end_date = $start_date->copy()->addDays(random_int(1, 15)),
            'created_at' => $start_date->copy()->subDays(random_int(1, 5)),
            'status' => Show::determineStatus($start_date, $end_date),
            'client_id' => Client::factory(),
        ];
    }
}
