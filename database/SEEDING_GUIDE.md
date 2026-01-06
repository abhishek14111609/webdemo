# Database Seeding Guide

## Overview

This guide provides instructions for seeding the WebDemo e-commerce database with test data. Database seeding is useful for development, testing, and demonstration purposes.

## Available Seeders

Laravel provides a convenient way to seed your database with test data using seed classes. The seeders should be located in the `database/seeders` directory.

## Creating Seeders

To create a new seeder, use the Laravel Artisan command:

```bash
php artisan make:seeder UserSeeder
```

This will create a new seeder class in the `database/seeders` directory.

## Recommended Seeding Order

When seeding the database, it's important to follow a specific order to maintain referential integrity. Here's the recommended order:

1. Users
2. Categories
3. Collections
4. Products
5. Collection-Product relationships
6. Reviews
7. Sliders
8. Inquiries

## Sample Seeder Implementation

Here's an example of how to implement a seeder for the Users table:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);
        
        // Create regular users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'city' => 'New York',
            'phone' => '1234567890',
            'gender' => 'Male',
        ]);
        
        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'city' => 'Los Angeles',
            'phone' => '0987654321',
            'gender' => 'Female',
        ]);
        
        // Create additional users with faker
        \App\Models\User::factory(10)->create();
    }
}
```

## Using Factories

Laravel factories provide a convenient way to generate large amounts of database records. Here's an example of a Product factory:

```php
<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        $price = $this->faker->randomFloat(2, 10, 1000);
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'price' => $price,
            'original_price' => $this->faker->randomElement([null, $price * 1.2]),
            'stock' => $this->faker->numberBetween(0, 100),
            'image' => 'products/' . $this->faker->numberBetween(1, 10) . '.jpg',
            'gallery' => json_encode([
                'products/' . $this->faker->numberBetween(1, 10) . '.jpg',
                'products/' . $this->faker->numberBetween(1, 10) . '.jpg',
            ]),
            'category_id' => Category::inRandomOrder()->first()->id,
            'badge' => $this->faker->randomElement([null, 'New', 'Sale', 'Hot']),
            'is_featured' => $this->faker->boolean(20),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
```

## Running Seeders

To run all seeders, use the following Artisan command:

```bash
php artisan db:seed
```

To run a specific seeder, use:

```bash
php artisan db:seed --class=UserSeeder
```

## Creating a Database Seeder

The main `DatabaseSeeder` class should call all other seeders in the correct order:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            CollectionSeeder::class,
            ProductSeeder::class,
            CollectionProductSeeder::class,
            ReviewSeeder::class,
            SliderSeeder::class,
            InquirySeeder::class,
        ]);
    }
}
```

## Refreshing the Database

To refresh the database (drop all tables and re-run migrations) and seed it:

```bash
php artisan migrate:fresh --seed
```

This is useful during development to start with a clean database.

## Seeding in Production

For production environments, you may want to create a separate seeder that only adds essential data:

```bash
php artisan db:seed --class=ProductionSeeder
```

## Conclusion

Proper database seeding is essential for development and testing. By following this guide, you can create realistic test data for your WebDemo e-commerce application.