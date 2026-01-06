<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sliders = [
            [
                'title' => 'Exquisite Diamond Jewelry',
                'description' => 'Discover our exclusive collection of handcrafted diamond jewelry that will make every moment special.',
                'image' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80',
                'button_text' => 'Shop Now',
                'button_link' => '/shop',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Eternal Love, Timeless Beauty',
                'description' => 'Celebrate your love with our stunning collection of engagement rings and wedding bands.',
                'image' => 'https://images.unsplash.com/photo-1591209662757-ab76fb411c70?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'button_text' => 'View Collection',
                'button_link' => '/category/engagement-rings',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Luxury Redefined',
                'description' => 'Experience the perfect blend of elegance and craftsmanship with our premium jewelry collection.',
                'image' => 'https://images.unsplash.com/photo-1589674668791-4889d2bba4c6?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'button_text' => 'New Arrivals',
                'button_link' => '/collections',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }
    }
}