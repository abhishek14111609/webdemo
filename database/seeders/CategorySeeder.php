<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Engagement Rings',
                'slug' => 'engagement-rings',
                'description' => 'Find the perfect engagement ring to symbolize your love and commitment.',
                'image' => 'https://images.unsplash.com/photo-1602173574767-37ac01994b8a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80',
                'sort_order' => 1,
            ],
            [
                'name' => 'Wedding Bands',
                'slug' => 'wedding-bands',
                'description' => 'Elegant wedding bands to celebrate your eternal love.',
                'image' => 'https://images.unsplash.com/photo-1602173572837-31acb37d7d58?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80',
                'sort_order' => 2,
            ],
            [
                'name' => 'Necklaces',
                'slug' => 'necklaces',
                'description' => 'Beautiful necklaces to complement any outfit.',
                'image' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80',
                'sort_order' => 3,
            ],
            [
                'name' => 'Earrings',
                'slug' => 'earrings',
                'description' => 'Stunning earrings to add sparkle to your look.',
                'image' => 'https://images.unsplash.com/photo-1602173574245-f2d4d9433a07?ixlib=rb-4.0.3&auto=format&fit=crop&w=1374&q=80',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
