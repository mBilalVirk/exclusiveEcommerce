<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $products = [
        [
            "name" => "Breed Dry Dog Food",
            "slug" => "breed-dry-dog-food",
            "description" => "High-quality dry dog food formulated for balanced nutrition and healthy growth.",
            "price" => 140,
            "discount_price" => 140,
            "image" => "image/Breed Dry Dog Food.png",
            "stars" => 5,
            "reviews_count" => 88,
            "is_new" => false,
            "category" => "Pets"
        ],
        [
            "name" => "CANON EOS DSLR Camera",
            "slug" => "canon-eos-dslr-camera",
            "description" => "Professional DSLR camera with high-resolution sensor and advanced autofocus capabilities.",
            "price" => 400,
            "discount_price" => 400,
            "image" => "image/CANON EOS DSLR Camera.png",
            "stars" => 4,
            "reviews_count" => 93,
            "is_new" => false,
            "category" => "Electronics"
        ],
        [
            "name" => "ASUS FHD Gaming Laptop",
            "slug" => "asus-fhd-gaming-laptop",
            "description" => "Powerful gaming laptop with Full HD display and high-performance graphics card.",
            "price" => 700,
            "discount_price" => 70,
            "image" => "image/ASUS FHD Gaming Laptop.png",
            "stars" => 5,
            "reviews_count" => 98,
            "is_new" => false,
            "category" => "Computing"
        ],
        [
            "name" => "Curology Product Set",
            "slug" => "curology-product-set",
            "description" => "Customized skincare set designed to target specific skin concerns for a clearer complexion.",
            "price" => 370,
            "discount_price" => 370,
            "image" => "image/Curology Product Set.png",
            "stars" => 5,
            "reviews_count" => 88,
            "is_new" => false,
            "category" => "Beauty"
        ],
        [
            "name" => "Kids Electric Car",
            "slug" => "kids-electric-car",
            "description" => "Fun and safe electric car for kids with realistic features and rechargeable battery.",
            "price" => 400,
            "discount_price" => 400,
            "image" => "image/Kids Electric Car.png",
            "stars" => 5,
            "reviews_count" => 88,
            "is_new" => true,
            "category" => "Toys"
        ],
        [
            "name" => "Jr. Zoom Soccer Cleats",
            "slug" => "jr-zoom-soccer-cleats",
            "description" => "Durable and comfortable soccer cleats designed for young athletes to improve performance.",
            "price" => 170,
            "discount_price" => 170,
            "image" => "image/Jr. Zoom Soccer Cleats.png",
            "stars" => 5,
            "reviews_count" => 44,
            "is_new" => false,
            "category" => "Sports"
        ],
        [
            "name" => "GP11 Shooter USB Gamepad",
            "slug" => "gp11-shooter-usb-gamepad",
            "description" => "Responsive USB gamepad with ergonomic design for an immersive gaming experience.",
            "price" => 200,
            "discount_price" => 200,
            "image" => "image/GP11 Shooter USB Gamepad.png",
            "stars" => 5,
            "reviews_count" => 31,
            "is_new" => true,
            "category" => "Gaming"
        ],
        [
            "name" => "Quilted Satin Jacket",
            "slug" => "quilted-satin-jacket",
            "description" => "Stylish quilted satin jacket offering warmth and a sleek look for any occasion.",
            "price" => 400,
            "discount_price" => 40,
            "image" => "image/Quilted Satin Jacket.png",
            "stars" => 5,
            "reviews_count" => 17,
            "is_new" => false,
            "category" => "Apparel"
        ]
    ];

    foreach ($products as $product) {
        Product::create($product);
    }
}
}
