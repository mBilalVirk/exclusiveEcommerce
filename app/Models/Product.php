<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'discount_price', 
        'image', 'stars', 'reviews_count', 'is_new'
    ];
}
