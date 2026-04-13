<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class FlashSaleProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'HAVIT HV-G92 Gamepad',
                'slug' => Str::slug('HAVIT HV-G92 Gamepad'),
                'description' => 'High performance gaming gamepad.',
                'price' => 160,
                'discount_price' => 120,
                'image' => 'image/g92-2-500x500.png',
                'stars' => 5,
                'reviews_count' => 88,
                'is_new' => false,
                'category' => 'Gaming',
            ],
            [
                'name' => 'AK-900 Wired Keyboard',
                'slug' => Str::slug('AK-900 Wired Keyboard'),
                'description' => 'Mechanical wired keyboard for gaming.',
                'price' => 170,
                'discount_price' => 150,
                'image' => 'image/ak-900-01-500x500.png',
                'stars' => 4,
                'reviews_count' => 73,
                'is_new' => false,
                'category' => 'Computing',
            ],
            [
                'name' => 'IPS LCD Gaming Monitor',
                'slug' => Str::slug('IPS LCD Gaming Monitor'),
                'description' => 'High refresh rate gaming monitor.',
                'price' => 400,
                'discount_price' => 370,
                'image' => 'image/led-tv.png',
                'stars' => 5,
                'reviews_count' => 98,
                'is_new' => false,
                'category' => 'Electronics',
            ],
            [
                'name' => 'S-Series Comfort Chair',
                'slug' => Str::slug('S-Series Comfort Chair'),
                'description' => 'Comfortable ergonomic chair.',
                'price' => 400,
                'discount_price' => 370,
                'image' => 'image/chairs.png',
                'stars' => 5,
                'reviews_count' => 88,
                'is_new' => false,
                'category' => 'Furniture',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}