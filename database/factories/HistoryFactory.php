<?php

namespace Database\Factories;

use App\Models\Borrowing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History>
 */
class HistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'borrowing_id' => Borrowing::factory(),
            'action' => $this->faker->randomElement(['borrow', 'return']),
            'action_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
