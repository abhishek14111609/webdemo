<?php

namespace Database\Seeders;

use App\Models\Collection;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    public function run(): void
    {
        $collections = [
            [
                'title' => 'Engagement',
                'slug' => 'engagement-rings',
                'description' => 'Symbolize your eternal love with our exquisite engagement rings, featuring the finest diamonds and precious metals.',
                'image' => 'https://images.unsplash.com/photo-1602173574767-37ac01994b8a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Wedding Bands',
                'slug' => 'wedding-bands',
                'description' => 'Celebrate your union with our elegant wedding bands, available in a variety of metals and styles.',
                'image' => 'https://images.unsplash.com/photo-1602173572837-31acb37d7d58?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Fine Jewelry',
                'slug' => 'fine-jewelry',
                'description' => 'Timeless pieces that elevate your style, crafted with the finest materials and exceptional craftsmanship.',
                'image' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80',
                'is_featured' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($collections as $collection) {
            Collection::create($collection);
        }
    }
}
