<?php
namespace Database\Factories;

use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportPage>
 */
class ReportPageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(['line-chart', 'bar-chart', 'pie-chart']),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->realText(),
            'report_id' => Report::factory()->forShow(),
            'meta' => [],
            'include_header' => true,
            'include_footer' => true,
        ];
    }
}
