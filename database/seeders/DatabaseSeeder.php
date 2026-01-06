<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            CollectionSeeder::class,
            ProductSeeder::class,
            SliderSeeder::class, // Add this line
        ]);
    }
}