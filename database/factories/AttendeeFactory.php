<?php
namespace Database\Factories;

use App\Models\Show;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendee>
 */
class AttendeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'badge_id' => $this->faker->uuid(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'job_title' => $this->faker->jobTitle(),
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'address_1' => $this->faker->streetAddress(),
            'address_2' => $this->faker->streetAddress(),
            'country' => $this->faker->country(),
            'city' => $this->faker->city(),
            'state' => $this->faker->city(),
            'zip_code' => $this->faker->postcode(),
            'has_permission' => $this->faker->boolean(),
            'meta' => json_encode([]),
            'notes' => $this->faker->paragraph(),
            'show_id' => Show::factory(),
        ];
    }
}
