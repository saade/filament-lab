<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)
            ->has(
                \App\Models\Book::factory(3)->has(\App\Models\Chapter::factory(5))
            )
            ->create();
    }
}
