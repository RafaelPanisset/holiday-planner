<?php

namespace Database\Factories;

use App\Models\HolidayPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HolidayPlan>
 */
class HolidayPlanFactory extends Factory
{
    /**
     * O modelo que esta fábrica está criando.
     *
     * @var string
     */
    protected $model = HolidayPlan::class;

    /**
     * Define o estado padrão do modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,  // Exemplo: 'Family Vacation'
            'description' => $this->faker->paragraph,  // Exemplo: 'A detailed description of the holiday plan.'
            'date' => $this->faker->date,  // Exemplo: '2024-08-07'
            'location' => $this->faker->city,  // Exemplo: 'Paris'
            'participants' => json_encode($this->faker->words(2, true)),  // Exemplo: '["John Doe", "Jane Smith"]'
        ];
    }
}
