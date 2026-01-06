<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get category IDs
        $engagementRingsId = Category::where('slug', 'engagement-rings')->first()->id;
        $weddingBandsId = Category::where('slug', 'wedding-bands')->first()->id;
        $necklacesId = Category::where('slug', 'necklaces')->first()->id;
        $earringsId = Category::where('slug', 'earrings')->first()->id;
        
        // Get collection IDs
        $engagementCollectionId = Collection::where('slug', 'engagement-rings')->first()->id;
        $weddingBandsCollectionId = Collection::where('slug', 'wedding-bands')->first()->id;
        $fineJewelryCollectionId = Collection::where('slug', 'fine-jewelry')->first()->id;
        
        $products = [
            [
                'name' => 'Classic Solitaire Ring',
                'slug' => 'classic-solitaire-ring',
                'description' => 'A timeless classic solitaire diamond ring, perfect for engagements and special occasions.',
                'price' => 1899.99,
                'original_price' => 2199.99,
                'stock' => 10,
                'image' => 'https://images.unsplash.com/photo-1603974379129-7b8164520079?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80',
                'category_id' => $engagementRingsId,
                'badge' => 'Sale',
                'is_featured' => true,
                'collections' => [$engagementCollectionId],
            ],
            [
                'name' => 'Eternity Diamond Band',
                'slug' => 'eternity-diamond-band',
                'description' => 'A beautiful eternity band with sparkling diamonds all around.',
                'price' => 1499.99,
                'original_price' => null,
                'stock' => 5,
                'image' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80',
                'category_id' => $weddingBandsId,
                'badge' => 'New',
                'is_featured' => true,
                'collections' => [$weddingBandsCollectionId],
            ],
            [
                'name' => 'Pearl Drop Earrings',
                'slug' => 'pearl-drop-earrings',
                'description' => 'Elegant pearl drop earrings that add sophistication to any outfit.',
                'price' => 899.99,
                'original_price' => 1099.99,
                'stock' => 15,
                'image' => 'https://images.unsplash.com/photo-1603974379129-7b8164520079?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80',
                'category_id' => $earringsId,
                'badge' => 'Popular',
                'is_featured' => true,
                'collections' => [$fineJewelryCollectionId],
            ],
            [
                'name' => 'Vintage Halo Pendant',
                'slug' => 'vintage-halo-pendant',
                'description' => 'A beautiful vintage-inspired halo pendant with sparkling diamonds.',
                'price' => 2499.99,
                'original_price' => null,
                'stock' => 8,
                'image' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80',
                'category_id' => $necklacesId,
                'badge' => null,
                'is_featured' => false,
                'collections' => [$fineJewelryCollectionId],
            ],
        ];

        foreach ($products as $productData) {
            $collectionIds = $productData['collections'];
            unset($productData['collections']);
            
            $product = Product::create($productData);
            $product->collections()->attach($collectionIds);
        }
    }
}
