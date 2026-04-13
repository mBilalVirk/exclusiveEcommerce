<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class BestSellingProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'The north coat',
                'slug' => Str::slug('The north coat'),
                'description' => 'Premium winter coat',
                'price' => 360,
                'discount_price' => 260,
                'image' => 'image/672462_ZAH9D_5626_002_100_0000_Light-The-North-Face-x-Gucci-coat 1.png',
                'stars' => 5,
                'reviews_count' => 47,
                'is_new' => false,
                'category' => 'Fashion',
            ],
            [
                'name' => 'Gucci duffle bag',
                'slug' => Str::slug('Gucci duffle bag'),
                'description' => 'Luxury duffle bag',
                'price' => 270,
                'discount_price' => 250,
                'image' => 'image/547953_9C2ST_8746_001_082_0000_Light-Gucci-Savoy-medium-duffle-bag 1.png',
                'stars' => 4,
                'reviews_count' => 33,
                'is_new' => false,
                'category' => 'Fashion',
            ],
            [
                'name' => 'RGB liquid CPU Cooler',
                'slug' => Str::slug('RGB liquid CPU Cooler'),
                'description' => 'High performance cooling system',
                'price' => 400,
                'discount_price' => 370,
                'image' => 'image/rgb-speaker.png',
                'stars' => 5,
                'reviews_count' => 98,
                'is_new' => false,
                'category' => 'Electronics',
            ],
            [
                'name' => 'Small BookSelf',
                'slug' => Str::slug('Small BookSelf'),
                'description' => 'Wooden bookshelf for home',
                'price' => 300,
                'discount_price' => 400,
                'image' => 'image/bookshelf.png',
                'stars' => 5,
                'reviews_count' => 87,
                'is_new' => false,
                'category' => 'Furniture',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}