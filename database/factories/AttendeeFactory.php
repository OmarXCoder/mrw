<?php
namespace Database\Factories;

use App\Models\Client;
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
            'company' => $this->faker->randomElement(['Apple', 'Google', 'Microsoft', 'Samsung', 'Toyota', 'Ford', 'Pfizer', 'LG', 'Philips']),
            'phone' => $this->faker->phoneNumber(),
            'address_line_1' => $this->faker->streetAddress(),
            'address_line_2' => $this->faker->streetAddress(),
            'country' => $this->faker->randomElement(['United States', 'Egypt', 'France', 'Italy', 'Germany', 'Turkey', 'Denmark', 'United Kingdom', 'Qatar']),
            'city' => $this->faker->city(),
            'state' => $this->faker->randomElement(['CA', 'MA', 'AZ', 'CO', 'FL', 'HI', 'ID', 'IN', 'MI', 'NJ', 'NV', 'OK', 'PR', 'TX']),
            'postal_code' => $this->faker->postcode(),
            'meta' => [],
            'notes' => $this->faker->paragraph(),
            'show_id' => Show::factory(),
            'client_id' => Client::factory(),
        ];
    }
}
