<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Borrowing>
 */
class BorrowingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $borrowDate = $this->faker->dateTimeBetween('-1 month', 'now');
        $dueDate = (clone $borrowDate)->modify('+7 days');
        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'borrow_date' => $borrowDate,
            'due_date' => $dueDate,
            'return_date' => $this->faker->optional()->dateTimeBetween($borrowDate, '+14 days'),
            'status' => $this->faker->randomElement(['dipinjam', 'dikembalikan', 'terlambat']),
        ];
    }
}
