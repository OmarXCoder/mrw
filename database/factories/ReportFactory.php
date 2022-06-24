<?php
namespace Database\Factories;

use App\Models\App;
use App\Models\Show;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(),
            'description' => $this->faker->realText(),
            'file_path' => null,
        ];
    }

    /**
     * Indicate that the report for a App\Models\Show
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forShow()
    {
        return $this->state(fn (array $attributes) => [
            'reportable_type' => Show::class,
            'reportable_id' => $show = Show::factory()->create(),
            'client_id' => $show->client_id,
        ]);
    }

    /**
     * Indicate that the report for a App\Models\App
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forApp()
    {
        return $this->state(fn (array $attributes) => [
            'reportable_type' => App::class,
            'reportable_id' => $app = App::factory()->create(),
            'client_id' => $app->client_id,
        ]);
    }
}
