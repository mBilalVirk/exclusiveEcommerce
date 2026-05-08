<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('product_id')
                  ->constrained('products')
                  ->onDelete('cascade');

            // Review Content
            $table->tinyInteger('rating')->unsigned()->comment('1 to 5 stars');
            $table->text('comment');

            // Moderation
            $table->boolean('is_approved')
                  ->default(true)
                  ->comment('Whether the review is visible to public');

            // Timestamps
            $table->timestamps();

            // Indexes for better performance
            $table->index(['product_id', 'is_approved']);
            $table->index(['user_id', 'product_id']);
            
            // Unique constraint: One review per user per product
            $table->unique(['user_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};