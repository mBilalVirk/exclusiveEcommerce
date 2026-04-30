<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Product extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'price', 'discount_price', 'image', 'stars', 'reviews_count', 'is_new' ,'stock','category',
        'is_available',];
    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }
}
