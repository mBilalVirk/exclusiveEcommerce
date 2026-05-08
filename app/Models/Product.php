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
    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class, 'product_id');   
    }


    // Add these relationships
public function reviews()
{
    return $this->hasMany(Review::class);
}

// Optional: Approved reviews only
public function approvedReviews()
{
    return $this->reviews()->where('is_approved', true);
}

// Accessor for average rating (if not storing in products table)
public function getAverageRatingAttribute()
{
    return $this->approvedReviews()->avg('rating') ?? 0;
}

public function getTotalReviewsAttribute()
{
    return $this->approvedReviews()->count();
}
}
