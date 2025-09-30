<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\History;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        // Buat 20 buku dummy
        Book::factory(20)->create();

        // Buat 10 peminjaman dummy dan history-nya
        Borrowing::factory(10)->create()->each(function ($borrowing) {
            History::factory()->create([
                'borrowing_id' => $borrowing->id,
                'action' => 'borrow',
                'action_date' => $borrowing->borrow_date,
            ]);

            if ($borrowing->status !== 'dipinjam') {
                History::factory()->create([
                    'borrowing_id' => $borrowing->id,
                    'action' => 'return',
                    'action_date' => $borrowing->return_date ?? now(),
                ]);
            }
        });
    }
}
